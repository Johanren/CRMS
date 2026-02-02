const { addKeyword, EVENTS } = require('@bot-whatsapp/bot')
const path = require('path')
const fs = require('fs')

const {
    buscarOCrearCliente,
    obtenerAsesorDisponible,
    obtenerProgramasPorEmpresa,
    guardarLeadFinal,
    crearNotificacionCRM
} = require('../services/lead.service')

const COD_EMPRESA = 1

const limpiarTelefono = (jid) =>
    jid.replace('@s.whatsapp.net', '').replace(/^57/, '')

const normalizarNombrePDF = (nombre) =>
    nombre.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase().trim()

const logStep = (label, data = null) => {
    console.log('\n==============================')
    console.log(`🧭 ${label}`)
    if (data) console.log(JSON.stringify(data, null, 2))
    console.log('==============================\n')
}

const flowLead = addKeyword(EVENTS.WELCOME)

    .addAnswer(
        `HOLA ¡¡👋 buen día. Gracias por comunicarte con el instituto Multitech, 
        iniciamos clases el 23 de febrero, por favor me brindas tu *nombre completo*
        para ayudarte con una atención personalizada 😃🤝`,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {

            const telefono = limpiarTelefono(ctx.from)
            const nombre = ctx.body.trim()

            const cliente = await buscarOCrearCliente(nombre, telefono)
            const asesor = await obtenerAsesorDisponible(COD_EMPRESA)

            await state.update({
                step: 'programas',
                cliente_id: cliente.id_cliente,
                cliente_nombre: `${cliente.nombres} ${cliente.apellidos}`,
                user_id: asesor.id_user,
                asesor_nombre: asesor.nombres
            })

            await flowDynamic([
                `Mucho gusto *${cliente.nombres}*, mi nombre es *${asesor.nombres}* asesora institucional, 
                Nuestros Programas Técnicos Laborales en Auxiliar: (Mostrar la carreras que tenemos en el foco)`,
                `Te voy a enviar nuestras técnicas disponibles 📚`
            ])
        })

    .addAnswer(
        '📚 *Nuestros Programas Técnicos Laborales:*',
        null,
        async (_, { state, flowDynamic }) => {

            const programas = await obtenerProgramasPorEmpresa(COD_EMPRESA)

            let texto = ''
            programas.forEach((p, i) => texto += `${i + 1}. ${p.nombre}\n`)
            texto += '\n✍️ Responde con el número del programa.'

            await state.update({ step: 'seleccion_programa', programas })
            await flowDynamic(texto)
        })

    .addAnswer(
        '⌛ *Esperando tu selección...*',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {

            const data = await state.getMyState()
            const msg = ctx.body.trim().toLowerCase()

            logStep('INPUT', { step: data.step, msg })

            /* ===== SELECCIÓN ===== */
            if (data.step === 'seleccion_programa') {

                const opcion = parseInt(msg)
                if (isNaN(opcion) || opcion < 1 || opcion > data.programas.length) {
                    await flowDynamic('❌ Opción inválida.')
                    return
                }

                const programa = data.programas[opcion - 1]

                await state.update({
                    step: 'confirmacion',
                    programa_id: programa.id,
                    programa_nombre: programa.nombre
                })

                await flowDynamic('📄 Cargando folleto...')

                const pdfName = normalizarNombrePDF(programa.nombre) + '.pdf'
                const pdfPath = path.join(process.cwd(), 'pdf', pdfName)

                if (fs.existsSync(pdfPath)) {
                    await flowDynamic({
                        body: `📄 *${programa.nombre}*`,
                        media: pdfPath,
                        delay: 800
                    })
                } else {
                    await flowDynamic('⚠️ El folleto no está disponible.')
                }

                await flowDynamic(
                    `¿Qué deseas hacer ahora?
1️⃣ Elegir otra carrera
2️⃣ *Listo*`
                )
                return
            }

            /* ===== CONFIRMACIÓN ===== */
            if (data.step === 'confirmacion') {

                if (msg === '1') {
                    await state.update({ step: 'programas' })
                    await flowDynamic('Perfecto 😊 volvamos a las carreras.')
                    return
                }

                if (msg === '2' || msg === 'listo') {
                    await guardarLeadFinal({
                        cliente_id: data.cliente_id,
                        user_id: data.user_id,
                        carrera_id: data.programa_id,
                        cod_emp: COD_EMPRESA
                    })

                    await flowDynamic([
                        '✅ *Registro completado*',
                        'Un asesor continuará tu proceso.',
                        'Gracias por confiar en Multitech 🤝'
                    ])

                    await state.clear()
                    logStep('FLOW FINALIZADO')
                }
            }
        })

module.exports = { flowLead }
