const { addKeyword } = require('@bot-whatsapp/bot')
const { guardarMensajeCliente } = require('../services/lead.service')

// 🔥 Captura cualquier mensaje SIN interferir
const flowCapture = addKeyword([''])
    .addAction(async (ctx) => {
        // Ignorar mensajes del bot
        if (ctx.fromMe) return

        const telefono = ctx.from
        const mensaje = ctx.body

        if (!mensaje) return

        console.log('📩 Mensaje capturado:', telefono, mensaje)

        await guardarMensajeCliente(telefono, mensaje)
    })

module.exports = { flowCapture }