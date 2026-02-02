const { createBot, createProvider, createFlow, addKeyword } = require('@bot-whatsapp/bot')
const QRPortalWeb = require('@bot-whatsapp/portal')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const JsonFileAdapter = require('@bot-whatsapp/database/json')

const express = require('express')
const cors = require('cors')

const { flowLead } = require('./flows/lead.flow')
const { flowCapture } = require('./flows/capture.flow')

const {
    guardarMensajeCliente,
    obtenerLeadPorConversacion
} = require('./services/lead.service')

// =======================
// 🌐 API EXPRESS
// =======================
const app = express()
app.use(cors())
app.use(express.json())

let sock // 🔥 SOCKET REAL DE BAILEYS

app.post('/send-message', async (req, res) => {
    try {
        const { conversacion_id, mensaje } = req.body

        if (!sock) {
            return res.status(503).json({ error: 'WhatsApp no conectado aún' })
        }

        const lead = await obtenerLeadPorConversacion(conversacion_id)
        if (!lead) {
            return res.status(404).json({ error: 'Conversación no encontrada' })
        }

        await sock.sendMessage(
            lead.telefono + '@s.whatsapp.net',
            { text: mensaje }
        )

        res.json({ ok: true })
    } catch (err) {
        console.error('❌ Error enviando mensaje:', err)
        res.status(500).json({ error: 'Error interno' })
    }
})

app.listen(3001, () => {
    console.log('🚀 API WhatsApp en http://localhost:3001')
})

// =======================
// 🤖 BOT WHATSAPP
// =======================
const flowCommands = addKeyword(['test'])
    .addAnswer('✅ Bot operativo')

const net = require('net')

const getFreePort = (startPort = 3000) => {
    return new Promise((resolve) => {
        const server = net.createServer()
        server.listen(startPort, () => {
            const { port } = server.address()
            server.close(() => resolve(port))
        })
        server.on('error', () => {
            resolve(getFreePort(startPort + 1))
        })
    })
}

const startApi = async () => {
    const port = await getFreePort(3001)

    app.listen(port, () => {
        console.log(`🚀 API WhatsApp en http://localhost:${port}`)
    })
}

const startQR = async () => {
    const qrPort = await getFreePort(3002)

    QRPortalWeb({ port: qrPort })
    console.log(`📷 QR Portal en http://localhost:${qrPort}`)
}


const main = async () => {
    const adapterDB = new JsonFileAdapter()

    const adapterFlow = createFlow([
        flowCommands,
        flowLead
    ])

    const adapterProvider = createProvider(
        BaileysProvider,
        { version: [2, 3000, 1027934701] }
    )

    // 🩹 Parche LID / grupos
    const originalSendMessage =
        adapterProvider.sendMessage.bind(adapterProvider)

    adapterProvider.sendMessage = async (to, content, options = {}) => {
        const jid = `${to}`

        if (jid.endsWith('@g.us') || jid.endsWith('@lid')) {
            const provider = adapterProvider
            options = { ...options, ...options?.options }

            if (options?.media) {
                return provider.sendMedia(jid, options.media, content)
            }

            return provider.sendText(jid, content)
        }

        return originalSendMessage(to, content, options)
    }

    createBot({
        flow: adapterFlow,
        provider: adapterProvider,
        database: adapterDB,
    })

    adapterProvider.on('ready', (instance) => {
        sock = instance.sock
        console.log('🟢 WhatsApp conectado correctamente')
    })

    await startApi()
    await startQR()
}

main()
