const mysql = require('mysql2/promise')

console.log('📦 lead.service.js cargado')

const pool = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'crm'
})

pool.getConnection()
    .then(() => console.log('✅ MySQL conectado'))
    .catch(err => console.error('❌ Error MySQL:', err))

async function obtenerEmpresas() {
    console.log('➡️ obtenerEmpresas() ejecutado')

    const [rows] = await pool.query(
        'SELECT id_emp AS id, nom_emp AS nombre FROM empresa'
    )

    console.log('📊 Empresas encontradas:', rows.length)
    console.log(rows)

    return rows
}

async function obtenerProgramasPorEmpresa(empresaId) {
    console.log('➡️ obtenerProgramasPorEmpresa()', empresaId)

    if (!empresaId) {
        console.warn('⚠️ empresaId vacío')
        return []
    }

    const [rows] = await pool.query(
        'SELECT cod_pro AS id, desc_pro AS nombre FROM programa WHERE emp_pro = ? AND act_pro = 1',
        [empresaId]
    )

    console.log('📊 Programas encontrados:', rows.length)
    console.log(rows)

    return rows
}

async function guardarLead(data) {

    console.log('➡️ guardarLead() ejecutado')
    console.log('📦 Data recibida:', data)

    const conn = await pool.getConnection()
    console.log('🔗 Conexión obtenida')

    try {
        await conn.beginTransaction()
        console.log('🟡 Transacción iniciada')
        const {
            identificacion,
            nombres,
            apellidos,
            telefono,
            email,
            carrera_id,
            cod_emp
        } = data

        /* =====================================================
           1️⃣ CLIENTE (buscar o crear)
        ===================================================== */

        const [clienteExistente] = await conn.query(
            `SELECT id_cliente 
             FROM cliente 
             WHERE identificacion = ? OR telefono_principal = ?
             LIMIT 1`,
            [identificacion, telefono]
        )

        let cliente_id

        if (clienteExistente.length > 0) {
            cliente_id = clienteExistente[0].id_cliente
        } else {
            const [clienteInsert] = await conn.query(
                `INSERT INTO cliente 
                (identificacion, nombres, apellidos, telefono_principal, email)
                VALUES (?, ?, ?, ?, ?)`,
                [identificacion, nombres, apellidos, telefono, email]
            )

            cliente_id = clienteInsert.insertId
        }

        /* =====================================================
           2️⃣ ASESOR CON MENOS LEADS
        ===================================================== */

        let user_id = null

        const [asesorConMenosLeads] = await conn.query(
            `SELECT l.user_id, COUNT(*) AS total
             FROM leads l
             INNER JOIN user u ON u.id_user = l.user_id
             INNER JOIN user_role ur ON ur.id_rol = u.rol_id
             WHERE l.cod_emp = ?
               AND ur.activo = 1
             GROUP BY l.user_id
             ORDER BY total ASC
             LIMIT 1`,
            [cod_emp]
        )

        if (asesorConMenosLeads.length > 0) {
            user_id = asesorConMenosLeads[0].user_id
        } else {
            const [asesorFallback] = await conn.query(
                `SELECT u.id_user
                 FROM user u
                 INNER JOIN user_role r ON u.rol_id = r.id_rol
                 WHERE r.nombre_rol LIKE '%asesor%'
                   AND r.activo = 1
                 ORDER BY u.id_user ASC
                 LIMIT 1`
            )

            if (asesorFallback.length > 0) {
                user_id = asesorFallback[0].id_user
            }
        }

        /* =====================================================
           3️⃣ INSERT LEAD
        ===================================================== */

        const [leadInsert] = await conn.query(
            `INSERT INTO leads
            (user_id, cliente_id, carrera_id, estado_leads_id, cod_emp, utm_source, utm_medium, utm_campaign)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                user_id,
                cliente_id,
                carrera_id,
                1,                // estado_leads_id
                cod_emp,
                'whatsapp',
                'bot',
                'chat'
            ]
        )

        await conn.query(
            `INSERT INTO chat_conversaciones (lead_id, user_id)
            VALUES (?, ?)`,
            [leadInsert.insertId, user_id]
        )

        await conn.commit()
        console.log('🟢 Transacción confirmada')
        return {
            success: true,
            lead_id: leadInsert.insertId,
            cliente_id,
            user_id
        }

    } catch (error) {
        console.error('🔥 ERROR en guardarLead:', error)
        await conn.rollback()
        throw error
    } finally {
        conn.release()
        console.log('🔓 Conexión liberada')
    }
}

async function obtenerLeadPorConversacion(conversacion_id) {
    const [rows] = await pool.query(`
        SELECT 
            cc.lead_id,
            l.id_lead,
            c.telefono_principal AS telefono
        FROM chat_conversaciones cc
        INNER JOIN leads l ON l.id_lead = cc.lead_id
        INNER JOIN cliente c ON c.id_cliente = l.cliente_id
        WHERE cc.lead_id = ?
        LIMIT 1;
    `, [conversacion_id])

    return rows[0] || null
}

async function guardarMensajeCliente(telefono, mensaje) {
    console.log('💬 guardarMensajeCliente()')

    if (!telefono || !mensaje) {
        console.warn('⚠️ Teléfono o mensaje vacío')
        return
    }

    // 🔥 Normalizar teléfono
    telefono = telefono
        .replace('@s.whatsapp.net', '')
        .replace(/^57/, '')

    const conn = await pool.getConnection()

    try {
        const [rows] = await conn.query(
            `
            SELECT cc.lead_id AS conversacion_id
            FROM chat_conversaciones cc
            INNER JOIN leads l ON l.id_lead = cc.lead_id
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id
            WHERE c.telefono_principal LIKE ?
            ORDER BY cc.id DESC
            LIMIT 1
            `,
            [`%${telefono}`]
        )

        if (rows.length === 0) {
            console.warn('⚠️ No hay conversación activa para', telefono)
            return
        }

        const conversacion_id = rows[0].conversacion_id

        await conn.query(
            `
            INSERT INTO chat_mensajes (conversacion_id, emisor, mensaje)
            VALUES (?, 'cliente', ?)
            `,
            [conversacion_id, mensaje]
        )

        console.log('✅ Mensaje cliente guardado')

    } catch (error) {
        console.error('🔥 Error guardarMensajeCliente:', error)
    } finally {
        conn.release()
    }
}

module.exports = { guardarLead, obtenerEmpresas, obtenerProgramasPorEmpresa, guardarMensajeCliente, obtenerLeadPorConversacion }
