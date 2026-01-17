const { createBot, createProvider, createFlow, addKeyword } = require('@bot-whatsapp/bot')

const QRPortalWeb = require('@bot-whatsapp/portal')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MockAdapter = require('@bot-whatsapp/database/mock')

// 🔹 Flujo secundario
const flowSecundario = addKeyword(['2', 'siguiente'])
    .addAnswer('📄 Aquí tenemos el flujo secundario')

// 🔹 Flujos informativos
const flowDocs = addKeyword(['doc', 'documentacion', 'documentación'])
    .addAnswer(
        [
            '📄 Aquí encuentras la documentación',
            'https://bot-whatsapp.netlify.app/',
            '\n*2* Para siguiente paso.',
        ],
        null,
        null,
        [flowSecundario]
    )

const flowTuto = addKeyword(['tutorial', 'tuto'])
    .addAnswer(
        [
            '🙌 Aquí encuentras un ejemplo rápido',
            'https://bot-whatsapp.netlify.app/docs/example/',
            '\n*2* Para siguiente paso.',
        ],
        null,
        null,
        [flowSecundario]
    )

const flowGracias = addKeyword(['gracias', 'grac'])
    .addAnswer(
        [
            '🚀 Puedes apoyar el proyecto',
            'https://opencollective.com/bot-whatsapp',
            'https://www.buymeacoffee.com/leifermendez',
            'https://www.patreon.com/leifermendez',
            '\n*2* Para siguiente paso.',
        ],
        null,
        null,
        [flowSecundario]
    )

const flowDiscord = addKeyword(['discord'])
    .addAnswer(
        [
            '🤪 Únete al Discord',
            'https://link.codigoencasa.com/DISCORD',
            '\n*2* Para siguiente paso.',
        ],
        null,
        null,
        [flowSecundario]
    )

// 🔹 Flow principal
const flowPrincipal = addKeyword(['hola', 'ole', 'alo'])
    .addAnswer('🙌 Hola, bienvenido a este *Chatbot*')
    .addAnswer(
        [
            'Te comparto los siguientes links:',
            '👉 *doc* ver documentación',
            '👉 *tutorial* ver tutorial',
            '👉 *gracias* apoyar el proyecto',
            '👉 *discord* unirte al Discord',
        ],
        null,
        null,
        [flowDocs, flowGracias, flowTuto, flowDiscord]
    )

// 🚀 MAIN
const main = async () => {
    const adapterDB = new MockAdapter()
    const adapterFlow = createFlow([flowPrincipal])
    const adapterProvider = createProvider(BaileysProvider)

    createBot({
        flow: adapterFlow,
        provider: adapterProvider,
        database: adapterDB,
    })

    QRPortalWeb()
}

main()
