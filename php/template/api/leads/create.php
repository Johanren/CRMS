<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

try {

    $conn->beginTransaction();

    function normalizarTelefono($telefono)
    {
        $telefono = preg_replace('/\D/', '', $telefono);
        return (substr($telefono, 0, 2) === '57')
            ? substr($telefono, 2)
            : $telefono;
    }

    $cod_emp          = $data['cod_emp'];
    $identificacion   = $data['identificacion'] ?? null;
    $telefono = normalizarTelefono($data['telefono_principal']);
    $nombres          = $data['nombres'];
    $apellidos        = $data['apellidos'];
    $email            = $data['email'] ?? null;
    $user_id          = $data['user_id'] ?? null;
    $carrera_id       = $data['carrera_id'];
    $horario_id       = $data['horario_id'];
    $estado_lead_id   = $data['estado_lead'];
    $utm_source       = $data['utm_source'] ?? null;
    $utm_medium       = $data['utm_medium'] ?? null;
    $utm_campaign     = $data['utm_campaign'] ?? null;

    $where = [];
    $params = [];

    /* 🔹 Identificación */
    if (!empty($identificacion)) {
        $where[] = "identificacion = ?";
        $params[] = $identificacion;
    }

    /* 🔹 Teléfono */
    if (!empty($telefono)) {
        $where[] = "telefono_principal = ?";
        $params[] = $telefono;
    }

    /* 🔴 Validar que al menos uno venga */
    if (empty($where)) {
        throw new Exception("Debe enviar identificación o teléfono");
    }

    /* 🔹 Construir SQL dinámico */
    $sql = "SELECT id_cliente 
        FROM cliente 
        WHERE " . implode(" OR ", $where) . "
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    /* 2️⃣ Crear cliente si no existe */
    if (!$cliente) {
        $sql = "INSERT INTO cliente 
                (nombres, apellidos, telefono_principal, identificacion, email)
                VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombres, $apellidos, $telefono, $identificacion, $email]);
        $cliente_id = $conn->lastInsertId();
    } else {
        $cliente_id = $cliente['id_cliente'];
    }

    /* 3️⃣ Definir Asesor */
    if (!empty($user_id)) {
        $asesor_id = $user_id;
    } else {
        $sql = "SELECT u.id_user
                FROM user u
                INNER JOIN user_role r ON r.id_rol = u.rol_id
                WHERE (r.nombre_rol LIKE '%asesor%' OR u.rol_id = 2)
                AND u.cod_emp = ?
                AND u.estado = 1
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cod_emp]);
        $asesor = $stmt->fetch(PDO::FETCH_ASSOC);
        $asesor_id = $asesor ? $asesor['id_user'] : null;
    }

    if (!$asesor_id) {
        throw new Exception("No se encontró asesor disponible");
    }

    /* 4️⃣ Foco */
    $sql = "SELECT foco FROM empresa WHERE id_emp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cod_emp]);
    $foco = $stmt->fetchColumn();

    /* 5️⃣ Crear lead */
    $sql = "INSERT INTO leads
    (user_id, cliente_id, carrera_id, horario_id, estado_leads_id, foco, cod_emp, utm_source, utm_medium, utm_campaign, fecha_creacion)
    VALUES (?,?,?,?,?,?,?,?,?,?,NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        18,
        $cliente_id,
        $carrera_id,
        $horario_id,
        $estado_lead_id,
        $foco,
        $cod_emp,
        $utm_source,
        $utm_medium,
        $utm_campaign,
    ]);

    $lead_id = $conn->lastInsertId();

    /* 6️⃣ Crear Nota automática (opcional) */
    if (!empty($data['tit_not']) && !empty($data['desc_not'])) {

        $sql = "INSERT INTO nota 
                (tit_nota, desc_not, id_lead, user_id, cod_emp)
                VALUES (?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['tit_not'],
            $data['desc_not'],
            $lead_id,
            $asesor_id,
            $cod_emp
        ]);
    }

    /* 7️⃣ Crear Notificación */
    $sql = "INSERT INTO notificaciones 
            (user_id, titulo, mensaje, modulo, referencia)
            VALUES (?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $asesor_id,
        'Nuevo Lead Asignado',
        'Se ha creado un nuevo lead y fue asignado a usted.',
        'leads-details.php',
        json_encode([
            'id' => $lead_id,
            'id_cliente' => $cliente_id
        ])
    ]);

    /* 7️⃣ Crear RST */
    $sql = "INSERT INTO rst_frm
            (lead_id, obs_rst, tipo_trans_id, user_id, cod_emp, bd_rst)
            VALUES (?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $lead_id,
        'Nuevo Lead Asignado Por TEO',
        2,
        $asesor_id,
        $cod_emp,
        1
    ]);

    $conn->commit();

    echo json_encode([
        'status' => 'ok',
        'lead_id' => $lead_id
    ]);
} catch (Exception $e) {

    $conn->rollBack();

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
