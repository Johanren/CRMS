const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason
} = require('@whiskeysockets/baileys')

const P = require('pino')

async function iniciarBot() {
    const { state, saveCreds } = await useMultiFileAuthState('./auth')

    const sock = makeWASocket({
        logger: P({ level: 'silent' }),
        auth: state,
        printQRInTerminal: true
    })

    sock.ev.on('creds.update', saveCreds)

    sock.ev.on('connection.update', ({ connection, lastDisconnect }) => {
        if (connection === 'open') {
            console.log('🟢 WhatsApp conectado (Baileys puro)')
        }

        if (connection === 'close') {
            const shouldReconnect =
                lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut
            if (shouldReconnect) iniciarBot()
        }
    })

    // 📩 MENSAJES ENTRANTES
    sock.ev.on('messages.upsert', async ({ messages }) => {
        const msg = messages[0]
        if (!msg.message || msg.key.fromMe) return

        const remoteJid = msg.key.remoteJid
        const texto =
            msg.message.conversation ||
            msg.message.extendedTextMessage?.text ||
            ''

        console.log('📩 Mensaje:', remoteJid, texto)

        // ❌ Ignorar LID
        if (remoteJid.endsWith('@lid')) {
            console.log('⚠️ JID temporal (LID), ignorado')
            return
        }

        // ✅ Responder SOLO a chats reales
        if (texto.toLowerCase() === 'ping') {
            await sock.sendMessage(remoteJid, { text: 'pong 🏓 desde Baileys' })
        }
    })
}

iniciarBot()
