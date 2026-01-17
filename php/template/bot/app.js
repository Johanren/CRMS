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
const flowTest = addKeyword(['test']).addAnswer('✅ Bot operativo')

const main = async () => {
    const adapterDB = new JsonFileAdapter()
    const adapterFlow = createFlow([
        flowTest,
        flowLead,
        flowCapture // 👈 SIEMPRE AL FINAL
    ])

    const adapterProvider = createProvider(BaileysProvider)

    createBot({
        flow: adapterFlow,
        provider: adapterProvider,
        database: adapterDB,
    })

    // 🔥 OBTENER SOCKET REAL CUANDO YA EXISTE
    adapterProvider.on('ready', (instance) => {
        sock = instance.sock
        console.log('🟢 WhatsApp conectado correctamente')
    })

    QRPortalWeb()
}

main()
