const mysql = require('mysql2/promise')

const pool = mysql.createPool({
    host: '195.35.61.37',
    user: 'u941333950_crm_dev',
    password: 'HG9;@#B?d4',
    database: 'u941333950_crm_dev'
})

/* ============================
   CLIENTE
============================ */

async function buscarOCrearCliente(nombreCompleto, telefono) {

    const [existente] = await pool.query(
        `SELECT id_cliente, nombres, apellidos 
         FROM cliente 
         WHERE telefono_principal = ?
         LIMIT 1`,
        [telefono]
    )

    if (existente.length) {
        return { ...existente[0], esNuevo: false }
    }

    const partes = nombreCompleto.split(' ')
    const nombres = partes.slice(0, 2).join(' ')
    const apellidos = partes.slice(2).join(' ') || ''

    const [insert] = await pool.query(
        `INSERT INTO cliente (nombres, apellidos, telefono_principal)
         VALUES (?, ?, ?)`,
        [nombres, apellidos, telefono]
    )

    return {
        id_cliente: insert.insertId,
        nombres,
        apellidos,
        esNuevo: true
    }
}

/* ============================
   ASESOR
============================ */

async function obtenerAsesorDisponible(cod_emp) {

    const [rows] = await pool.query(`
        SELECT u.id_user, u.nombres, COUNT(l.id_lead) AS total
        FROM user u
        INNER JOIN user_role ur ON ur.id_rol = u.rol_id
        LEFT JOIN leads l ON l.user_id = u.id_user AND l.cod_emp = ?
        WHERE ur.activo = 1
        GROUP BY u.id_user
        ORDER BY total ASC
        LIMIT 1
    `, [cod_emp])

    return rows[0]
}

/* ============================
   PROGRAMAS
============================ */

async function obtenerProgramasPorEmpresa(cod_emp) {
    const [rows] = await pool.query(
        `SELECT cod_pro AS id, desc_pro AS nombre
         FROM programa
         WHERE emp_pro = ? AND act_pro = 1`,
        [cod_emp]
    )
    return rows
}

/* ============================
   PROGRAMAS y HORARIOS
============================ */

async function obtenerProgramasYHorariosPorFoco(emp_foc, id_foc) {
    const [rows] = await pool.query(`
        SELECT
            fd.prog_fde AS programa_id,
            p.desc_pro AS programa,
            fd.jorn_fde AS horario_id,
            h.descripcion AS horario
        FROM foco_detalle fd
        INNER JOIN programa p ON p.cod_pro = fd.prog_fde
        INNER JOIN horario h ON h.id_horario = fd.jorn_fde
        INNER JOIN foco f ON f.id_foc = fd.foc_fde
        WHERE f.emp_foc = ? AND f.id_foc = ?
        ORDER BY p.desc_pro, h.descripcion
    `, [emp_foc, id_foc])

    // Agrupar por programa
    const data = {}
    rows.forEach(r => {
        if (!data[r.programa_id]) {
            data[r.programa_id] = {
                id: r.programa_id,
                nombre: r.programa,
                horarios: []
            }
        }
        data[r.programa_id].horarios.push({
            id: r.horario_id,
            nombre: r.horario
        })
    })

    return Object.values(data)
}

/* ============================
   LEAD FINAL
============================ */

async function guardarLeadFinal(data) {
    const [result] = await pool.query(
        `INSERT INTO leads 
        (user_id, cliente_id, carrera_id, horario_id, estado_leads_id, cod_emp, utm_source, utm_medium, utm_campaign)
        VALUES (?, ?, ?, ? , ? , ? ,?,?,?)`,
        [data.user_id, data.cliente_id, data.carrera_id, data.horario_id, data.estado_leads_id, data.cod_emp, 'whatsapp', 'bot', 'chat']
    )

    // Retornamos el ID generado
    return result.insertId
}

/* ============================
   NOTIFICACIÓN CRM
============================ */

async function crearNotificacionCRM(data) {
    await pool.query(
        `INSERT INTO notificaciones 
        (user_id, titulo, mensaje, modulo, referencia)
        VALUES (?, ?, ?, ?, ?)`,
        [
            data.user_id,
            data.titulo,
            data.mensaje,
            data.modulo,
            data.referencia
        ]
    )
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

module.exports = {
    buscarOCrearCliente,
    obtenerAsesorDisponible,
    obtenerProgramasPorEmpresa,
    guardarLeadFinal,
    crearNotificacionCRM,
    obtenerLeadPorConversacion,
    guardarMensajeCliente,
    obtenerProgramasYHorariosPorFoco
}
