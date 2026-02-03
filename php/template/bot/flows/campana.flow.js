const { addKeyword, EVENTS } = require('@bot-whatsapp/bot')
const {
    buscarOCrearCliente,
    obtenerAsesorDisponible,
    guardarLeadFinal,
    crearNotificacionCRM,
    obtenerProgramasYHorariosPorFoco
} = require('../services/lead.service')

const COD_EMPRESA = 1
const ID_FOCO_INFANCIA = 55 
const ID_PROGRAMA_INFANCIA = 1 
const NOMBRE_PROGRAMA = "CONTABILIDAD"

const timers = {}

const stopInactivity = (from) => {
    if (timers[from]) clearTimeout(timers[from])
}

const startInactivity = (from, ctx, { gotoFlow, state }) => {
    stopInactivity(from)
    timers[from] = setTimeout(async () => {
        console.log(`⏳ Inactividad en: ${from}`)
        await state.clear()
        return gotoFlow(flowInactividad)
    }, 300000) 
}

const flowInactividad = addKeyword(EVENTS.ACTION)
    .addAnswer('⏳ Tu sesión ha expirado por inactividad. Si aún deseas información, por favor escribe de nuevo.')

// --- FLUJO DE HORARIOS ---
const flowHorarios = addKeyword(EVENTS.ACTION)
    .addAnswer('Buscando horarios disponibles...', null, async (ctx, { state, flowDynamic, gotoFlow }) => {
        // Movimos la lógica aquí para asegurar que se ejecute al entrar al flujo
        const data = await state.getMyState()
        const asesor = await obtenerAsesorDisponible(COD_EMPRESA)
        const programas = await obtenerProgramasYHorariosPorFoco(COD_EMPRESA, ID_FOCO_INFANCIA)
        const miPrograma = programas.find(p => p.id == ID_PROGRAMA_INFANCIA)

        if (!miPrograma) {
            return await flowDynamic("Lo sentimos, este programa no está disponible actualmente.")
        }

        await state.update({
            user_id: asesor.id_user,
            asesor_nombre: asesor.nombres,
            programa: miPrograma
        })

        let msg = `Mucho gusto *${data.cliente_nombre}*, te atenderá *${asesor.nombres}*. 😊\n\n`
        msg += `Para *${NOMBRE_PROGRAMA}*, tenemos estos horarios disponibles:\n`
        miPrograma.horarios.forEach((h, i) => { msg += `${i + 1}. ${h.nombre}\n` })
        msg += `\nResponde con el *número* del horario que prefieras.`

        await flowDynamic(msg)
        startInactivity(ctx.from, ctx, { gotoFlow, state })
    })
    .addAnswer(
        'Escribe el número aquí:', 
        { capture: true },
        async (ctx, { state, flowDynamic, fallBack }) => {
            const opcion = parseInt(ctx.body)
            const data = await state.getMyState()

            if (!data.programa || !opcion || opcion < 1 || opcion > data.programa.horarios.length) {
                return fallBack('❌ Opción inválida. Elige un número de la lista.')
            }

            const horario = data.programa.horarios[opcion - 1]
            await state.update({ horario })

            await flowDynamic(`Excelente. 💰 *¿Cómo te gustaría financiar tu carrera?*\n1️⃣ Contado\n2️⃣ Crédito\n\nResponde 1 o 2`)
            startInactivity(ctx.from, ctx, { gotoFlow, state })
        }
    )
    .addAnswer(
        'Finalizando registro...',
        { capture: true },
        async (ctx, { state, flowDynamic, fallBack }) => {
            const opcion = parseInt(ctx.body)
            const data = await state.getMyState()

            if (![1, 2].includes(opcion)) return fallBack('❌ Responde 1 o 2.')

            const lead_id = await guardarLeadFinal({
                user_id: data.user_id,
                cliente_id: data.cliente_id,
                carrera_id: data.programa.id,
                horario_id: data.horario.id,
                estado_leads_id: 2,
                cod_emp: COD_EMPRESA
            })

            await crearNotificacionCRM({
                user_id: data.user_id,
                titulo: `👶 NUEVO LEAD: ${NOMBRE_PROGRAMA}`,
                mensaje: `${data.cliente_nombre} en ${NOMBRE_PROGRAMA}.`,
                modulo: 'leads-details.php',
                referencia: JSON.stringify({ id: lead_id, id_cliente: data.cliente_id })
            })

            await flowDynamic(`✅ ¡Perfecto! El asesor *${data.asesor_nombre}* te contactará pronto.`)
            await state.clear()
        }
    )

// --- FLUJO DE ENTRADA ---
const flowInfancia = addKeyword(['CONTABILIDAD', 'contabilidad'])
    .addAction(async (ctx, { state, flowDynamic, gotoFlow }) => {
        const telefono = ctx.from.replace('@s.whatsapp.net', '').replace(/^57/, '')
        const cliente = await buscarOCrearCliente(null, telefono)
        
        await flowDynamic(`¡Hola! 👋 Veo que te interesa nuestra carrera de *${NOMBRE_PROGRAMA}*. 🎓`)

        if (cliente.id_cliente && cliente.nombres) {
            await state.update({ 
                cliente_id: cliente.id_cliente, 
                cliente_nombre: cliente.nombres 
            })
            await flowDynamic(`¡Qué gusto verte de nuevo, *${cliente.nombres}*! 😊`)
            return gotoFlow(flowHorarios) 
        } 
    })
    .addAnswer(
        'Para brindarte una atención personalizada, ¿podrías decirme tu *nombre completo*? 😃🤝',
        { capture: true },
        async (ctx, { state, gotoFlow }) => {
            const nombre = ctx.body.trim()
            const telefono = ctx.from.replace('@s.whatsapp.net', '').replace(/^57/, '')
            
            const cliente = await buscarOCrearCliente(nombre, telefono)
            await state.update({ 
                cliente_id: cliente.id_cliente, 
                cliente_nombre: cliente.nombres 
            })
            
            startInactivity(ctx.from, ctx, { gotoFlow, state })
            return gotoFlow(flowHorarios) 
        }
    )

module.exports = { flowInfancia, flowHorarios, flowInactividad }