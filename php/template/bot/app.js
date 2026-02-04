const { createBot, createProvider, createFlow, addKeyword } = require('@bot-whatsapp/bot')
const QRPortalWeb = require('@bot-whatsapp/portal')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const JsonFileAdapter = require('@bot-whatsapp/database/json')

const express = require('express')
const cors = require('cors')

const { flowLead, flowInactividadLead } = require('./flows/lead.flow')
const { flowInfancia, flowHorarios, flowInactividad } = require('./flows/campana.flow')
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

console.log('version 1.1')

const main = async () => {
    try {
        console.log('--- Iniciando Adaptadores ---');
        const adapterDB = new JsonFileAdapter();
        const adapterFlow = createFlow([flowCommands, flowLead, flowInactividadLead, flowInfancia, flowHorarios, flowInactividad]);

        console.log('--- Configurando Provider ---');
        const adapterProvider = createProvider(BaileysProvider, {
            version: [2, 3000, 1027934701],
            writeQR: true,
        });

        // Agrega este log para saber si el provider emite algo
        adapterProvider.on('qr', (qr) => {
            console.log('🔥 NUEVO QR GENERADO:', qr);
        });

        adapterProvider.on('auth_failure', (msg) => {
            console.error('❌ ERROR DE AUTENTICACIÓN:', msg);
        });

        console.log('--- Creando Bot ---');
        await createBot({
            flow: adapterFlow,
            provider: adapterProvider,
            database: adapterDB,
        });

        console.log('--- Bot Creado, esperando QR... ---');
        QRPortalWeb({ port: 3005 });

    } catch (error) {
        console.error('💥 ERROR CRÍTICO EN MAIN:', error);
    }
}

main()
