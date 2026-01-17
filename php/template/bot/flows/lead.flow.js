const { addKeyword, EVENTS } = require('@bot-whatsapp/bot')
const { guardarLead, obtenerEmpresas, obtenerProgramasPorEmpresa } = require('../services/lead.service')

const logStep = (step) => {
    console.log(`📍 FLOW STEP -> ${step}`)
}

const flowLead = addKeyword(EVENTS.WELCOME)

    // 1️⃣ Saludo
    .addAnswer(
        '👋 Hola, ¿cómo estás?\n¿Buscas *Envision*?',
        { capture: true },
        async (ctx) => {
            logStep('1️⃣ Saludo')
            console.log('Usuario dice:', ctx.body)
        }
    )

    // 2️⃣ Empresa
    .addAnswer(
        '🏢 Cargando empresas...',
        { capture: false },
        async (_, { flowDynamic, state }) => {
            logStep('2️⃣ Mostrar Empresas')

            const empresas = await obtenerEmpresas()

            if (!empresas || empresas.length === 0) {
                await flowDynamic('❌ No hay empresas disponibles en este momento.')
                return
            }

            let texto = '🏢 Elige la empresa para tus cursos:\n\n'
            empresas.forEach((e, i) => {
                texto += `${i + 1}. ${e.nombre}\n`
            })
            texto += '\n✍️ Responde solo con el número.'

            // 🔥 AQUÍ SE ENVÍA EL MENSAJE REAL
            await flowDynamic(texto)

            // Guardamos las empresas para el siguiente paso
            await state.update({ empresas })
        }
    )
    .addAnswer(
        '👇 Escribe el número de la empresa',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('2️⃣ Captura Empresa')

            const { empresas } = await state.getMyState()
            const opcion = parseInt(ctx.body)

            if (!empresas || isNaN(opcion) || opcion < 1 || opcion > empresas.length) {
                await flowDynamic('❌ Opción inválida. Intenta nuevamente.')
                return
            }

            const empresa = empresas[opcion - 1]

            await state.update({
                empresa_id: empresa.id,
                empresa: empresa.nombre
            })

            console.log('✅ Empresa seleccionada:', empresa)
        }
    )

    // 3️⃣ Nombres
    .addAnswer(
        '🧑 Nombres:',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('3️⃣ Nombres')

            if (ctx.body.length < 2) {
                console.log('❌ Nombre inválido')
                await flowDynamic('❌ Ingresa un nombre válido.')
                return
            }

            await state.update({ nombres: ctx.body })
        }
    )

    // 4️⃣ Apellidos
    .addAnswer(
        '🧑 Apellidos:',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('4️⃣ Apellidos')

            if (ctx.body.length < 2) {
                await flowDynamic('❌ Ingresa apellidos válidos.')
                return
            }

            await state.update({ apellidos: ctx.body })
        }
    )

    // 5️⃣ Cédula
    .addAnswer(
        '🆔 Número de cédula:',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('5️⃣ Cédula')

            if (!/^\d{5,15}$/.test(ctx.body)) {
                await flowDynamic('❌ Cédula inválida. Solo números.')
                return
            }

            await state.update({ cedula: ctx.body })
        }
    )

    // 6️⃣ Correo
    .addAnswer(
        '📧 Correo electrónico:',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('6️⃣ Correo')

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(ctx.body)) {
                await flowDynamic('❌ Correo inválido.')
                return
            }

            await state.update({ email: ctx.body })
        }
    )

    // 7️⃣ Programas
    .addAnswer(
        '📚 Cargando programas...',
        null,
        async (_, { state, flowDynamic }) => {
            logStep('7️⃣ Mostrar Programas')

            const { empresa_id } = await state.getMyState()
            const programas = await obtenerProgramasPorEmpresa(empresa_id)

            if (!programas || programas.length === 0) {
                await flowDynamic('❌ No hay programas disponibles para esta empresa.')
                return
            }

            let texto = '📚 Selecciona el programa que más te gusta:\n\n'
            programas.forEach((p, i) => {
                texto += `${i + 1}. ${p.nombre}\n`
            })
            texto += '\n✍️ Responde solo con el número.'

            await flowDynamic(texto)

            await state.update({ programas })
        }
    )
    .addAnswer(
        '👇 Escribe el número del programa',
        { capture: true },
        async (ctx, { state, flowDynamic }) => {
            logStep('7️⃣ Captura Programa')

            const { programas } = await state.getMyState()
            const opcion = parseInt(ctx.body)

            if (!programas || isNaN(opcion) || opcion < 1 || opcion > programas.length) {
                await flowDynamic('❌ Opción inválida. Intenta nuevamente.')
                return
            }

            const programa = programas[opcion - 1]

            await state.update({
                programa_id: programa.id,
                programa: programa.nombre
            })

            console.log('✅ Programa seleccionado:', programa)
        }
    )

    // 8️⃣ Guardar lead
    .addAnswer(
        '✅ Estamos registrando tu información...',
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

            await flowDynamic([
                '🎉 ¡Registro exitoso!',
                'Un asesor se comunicará contigo muy pronto.',
                '🕐 Gracias por confiar en Envision.'
            ])
        }
    )

module.exports = { flowLead }
