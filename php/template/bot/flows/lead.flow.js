const { addKeyword, EVENTS } = require('@bot-whatsapp/bot')
const { guardarLead, obtenerEmpresas, obtenerProgramasPorEmpresa } = require('../services/lead.service')

const TIMEOUT_MINUTES = 5

const isTimeout = (state) => {
    const last = state.lastInteraction || Date.now()
    return Date.now() - last > TIMEOUT_MINUTES * 60 * 1000
}

const resetFlow = async (state, flowDynamic, msg = '🔄 Reiniciamos la conversación.') => {
    await state.clear()
    await flowDynamic([
        msg,
        'Escríbenos cualquier mensaje para comenzar de nuevo 👋'
    ])
}

const touch = async (state) => {
    await state.update({ lastInteraction: Date.now() })
}

const logStep = (step) => {
    console.log(`📍 FLOW STEP -> ${step}`)
}

const flowLead = addKeyword(EVENTS.WELCOME)

    .addAnswer(
        '👋 Hola, ¿cómo estás?\n¿Buscas *Envision*?',
        { capture: true },
        async (ctx, { state }) => {
            logStep('1️⃣ Saludo')
            await state.update({ step: 'empresa' })
            await touch(state)
        }
    )

    // =====================
    // 🏢 EMPRESAS
    // =====================
    .addAnswer(
        '🏢 Cargando empresas...',
        null,
        async (_, { state, flowDynamic }) => {
            if (isTimeout(await state.getMyState())) {
                return resetFlow(state, flowDynamic, '⌛ Pasó mucho tiempo.')
            }

            const empresas = await obtenerEmpresas()

            if (!empresas?.length) {
                await flowDynamic('❌ No hay empresas disponibles.')
                return
            }

            let texto = '🏢 Elige la empresa:\n\n'
            empresas.forEach((e, i) => texto += `${i + 1}. ${e.nombre}\n`)
            texto += '\n✍️ Responde con el número.'

            await state.update({ empresas, step: 'empresa' })
            await flowDynamic(texto)
        }
    )

    .addAnswer(
        '👇 Número de la empresa',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            if (data.step !== 'empresa') {
                return resetFlow(state, flowDynamic)
            }

            const opcion = parseInt(ctx.body)
            if (!data.empresas || isNaN(opcion) || opcion < 1 || opcion > data.empresas.length) {
                await flowDynamic('❌ Opción inválida. Escribe solo el número.')
                return
            }

            const empresa = data.empresas[opcion - 1]
            await state.update({
                empresa_id: empresa.id,
                empresa: empresa.nombre,
                step: 'nombres'
            })

            await flowDynamic('🧑 Escribe tus *nombres*:')
        }
    )

    // =====================
    // 🧑 NOMBRES
    // =====================
    .addAnswer(
        null,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            if (data.step !== 'nombres' || ctx.body.length < 2) {
                await flowDynamic('❌ Nombre inválido. Intenta nuevamente.')
                return
            }

            await state.update({ nombres: ctx.body, step: 'apellidos' })
            await flowDynamic('🧑 Escribe tus *apellidos*:')
        }
    )

    // =====================
    // 🧑 APELLIDOS
    // =====================
    .addAnswer(
        null,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            if (data.step !== 'apellidos' || ctx.body.length < 2) {
                await flowDynamic('❌ Apellidos inválidos.')
                return
            }

            await state.update({ apellidos: ctx.body, step: 'cedula' })
            await flowDynamic('🆔 Número de cédula:')
        }
    )

    // =====================
    // 🆔 CÉDULA
    // =====================
    .addAnswer(
        null,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            if (data.step !== 'cedula' || !/^\d{5,15}$/.test(ctx.body)) {
                await flowDynamic('❌ Cédula inválida. Solo números.')
                return
            }

            await state.update({ cedula: ctx.body, step: 'email' })
            await flowDynamic('📧 Correo electrónico:')
        }
    )

    // =====================
    // 📧 EMAIL
    // =====================
    .addAnswer(
        null,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            if (
                data.step !== 'email' ||
                !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(ctx.body)
            ) {
                await flowDynamic('❌ Correo inválido.')
                return
            }

            await state.update({ email: ctx.body, step: 'programa' })
        }
    )

    // =====================
    // 📚 PROGRAMAS
    // =====================
    .addAnswer(
        '📚 Cargando programas...',
        null,
        async (_, { state, flowDynamic }) => {
            const data = await state.getMyState()
            if (data.step !== 'programa') return

            const programas = await obtenerProgramasPorEmpresa(data.empresa_id)
            if (!programas?.length) {
                await flowDynamic('❌ No hay programas.')
                return
            }

            let texto = '📚 Elige el programa:\n\n'
            programas.forEach((p, i) => texto += `${i + 1}. ${p.nombre}\n`)
            texto += '\n✍️ Responde con el número.'

            await state.update({ programas, step: 'programa_select' })
            await flowDynamic(texto)
        }
    )

    .addAnswer(
        null,
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()
            await touch(state)

            const opcion = parseInt(ctx.body)
            if (
                data.step !== 'programa_select' ||
                isNaN(opcion) ||
                opcion < 1 ||
                opcion > data.programas.length
            ) {
                await flowDynamic('❌ Opción inválida.')
                return
            }

            const programa = data.programas[opcion - 1]
            await state.update({
                programa_id: programa.id,
                programa: programa.nombre,
                step: 'guardar'
            })
        }
    )

    // =====================
    // 💾 GUARDAR + CRM
    // =====================
    .addAnswer(
        '✅ Registrando información...',
        null,
        async (ctx, { state, flowDynamic }) => {
            const data = await state.getMyState()

            await guardarLead({
                identificacion: data.cedula,
                nombres: data.nombres,
                apellidos: data.apellidos,
                telefono: ctx.from,
                email: data.email,
                carrera_id: data.programa_id,
                cod_emp: data.empresa_id
            })

            // 🔗 HOOK CRM
            // await axios.post('https://tu-crm/api/leads', data)

            await flowDynamic([
                '🎉 ¡Registro exitoso!',
                'Un asesor se comunicará contigo.',
                '🕐 Gracias por confiar en Envision.'
            ])

            await state.clear()
        }
    )


module.exports = { flowLead }
