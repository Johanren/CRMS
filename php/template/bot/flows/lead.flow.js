const { addKeyword, EVENTS } = require('@bot-whatsapp/bot')

const {
    buscarOCrearCliente,
    obtenerAsesorDisponible,
    obtenerProgramasYHorariosPorFoco,
    guardarLeadFinal,
    crearNotificacionCRM
} = require('../services/lead.service')

/* =========================
   CONFIG
========================= */
const COD_EMPRESA = 1
const ID_FOCO = 55

const limpiarTelefono = jid =>
    jid.replace('@s.whatsapp.net', '').replace(/^57/, '')

/* =========================
   HELPERS
========================= */
const extraerNumero = (texto = '') => {
    const match = texto.match(/\d+/)
    return match ? parseInt(match[0]) : null
}

/* =========================
   FLOW
========================= */
const flowLead = addKeyword(EVENTS.WELCOME)
    .addAnswer(
        `HOLA ¡¡👋 buen día.
Gracias por comunicarte con el instituto *Multitech*,
📅 iniciamos clases el *23 de febrero*.

Por favor indícame tu *nombre completo* para brindarte una atención personalizada 😃🤝`,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const telefono = limpiarTelefono(ctx.from)
            const nombre = ctx.body.trim()

            const cliente = await buscarOCrearCliente(nombre, telefono)
            const asesor = await obtenerAsesorDisponible(COD_EMPRESA)
            const programas = await obtenerProgramasYHorariosPorFoco(COD_EMPRESA, ID_FOCO)

            await state.update({
                cliente_id: cliente.id_cliente,
                cliente_nombre: cliente.nombres,
                user_id: asesor.id_user,
                asesor_nombre: asesor.nombres,
                programas
            })

            let msg = `Mucho gusto *${cliente.nombres}* 😊\nMi nombre es *${asesor.nombres}*.\n\n🎓 *Programas:* \n`
            programas.forEach((p, i) => { msg += `${i + 1}. ${p.nombre}\n` })
            msg += `\n✍️ Responde con el *número*`

            await flowDynamic(msg)
        }
    )
    .addAnswer(
        'Esperando selección de programa...',
        { capture: true },
        async (ctx, { state, flowDynamic, fallBack }) => {
            const opcion = extraerNumero(ctx.body)
            const data = await state.getMyState()

            if (!opcion || opcion < 1 || opcion > data.programas.length) {
                return fallBack('❌ Opción inválida, intenta nuevamente.')
            }

            const programa = data.programas[opcion - 1]
            await state.update({ programa })

            let msg = `📚 *${programa.nombre}* - Horarios:\n\n`
            programa.horarios.forEach((h, i) => { msg += `${i + 1}. ${h.nombre}\n` })
            msg += `\n✍️ Responde con el *número del horario*`

            await flowDynamic(msg)
        }
    )
    .addAnswer(
        'Esperando selección de horario...',
        { capture: true },
        async (ctx, { state, flowDynamic, fallBack }) => {
            const opcion = extraerNumero(ctx.body)
            const data = await state.getMyState()

            if (!opcion || opcion < 1 || opcion > data.programa.horarios.length) {
                return fallBack('❌ Horario inválido, elige uno de la lista.')
            }

            const horario = data.programa.horarios[opcion - 1]
            await state.update({ horario })

            await flowDynamic(`💰 *Formas de pago:*\n1️⃣ Contado\n2️⃣ Crédito\n\nResponde 1 o 2`)
        }
    )
    .addAnswer(
        'Esperando método de pago...',
        { capture: true },
        async (ctx, { state, flowDynamic, fallBack }) => {
            const opcion = extraerNumero(ctx.body)
            const data = await state.getMyState()

            if (![1, 2].includes(opcion)) {
                return fallBack('❌ Opción inválida, responde 1 o 2.')
            }

            // ... dentro del flujo, en la parte final
            const lead_id = await guardarLeadFinal({
                user_id: data.user_id,
                cliente_id: data.cliente_id,
                carrera_id: data.programa.id,
                horario_id: data.horario.id,
                estado_leads_id: 2,
                cod_emp: COD_EMPRESA
            })

            // Ahora enviamos la notificación usando ese lead_id
            await crearNotificacionCRM({
                user_id: data.user_id,
                titulo: '🔥 Nuevo Lead Interesado',
                mensaje: `${data.cliente_nombre} en ${data.programa.nombre}`,
                modulo: 'leads-details.php',
                // Usamos el ID recién creado aquí
                referencia: JSON.stringify({
                    id: lead_id,
                    id_cliente: data.cliente_id
                })
            })

            await flowDynamic(`✅ *¡Gracias!* Información enviada a ${data.asesor_nombre}.`)
            await state.clear()
        }
    )

module.exports = { flowLead }
