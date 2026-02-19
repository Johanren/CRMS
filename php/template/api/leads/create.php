<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

$cod_emp = $data['cod_emp'];
$identificacion = $data['identificacion'];
$telefono = $data['telefono_principal'];
$nombres = $data['nombres'];
$apellidos = $data['apellidos'];
$user_id = $data['user_id'];
$carrera_id = $data['carrera_id'];
$horario_id = $data['horario_id'];
$estado_lead_id = $data['estado_lead'];
$utm_source = $data['utm_source'];
$utm_medium = $data['utm_medium'];
$utm_campaign = $data['utm_campaign'];

/* 1️⃣ Buscar cliente */
$sql = "SELECT id_cliente FROM cliente 
        WHERE identificacion = ? OR telefono_principal = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$identificacion, $telefono]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

/* 2️⃣ Crear cliente si no existe */
if (!$cliente) {
    $sql = "INSERT INTO cliente 
            (nombres, apellidos, telefono_principal, identificacion)
            VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombres, $apellidos, $telefono, $identificacion]);
    $cliente_id = $conn->lastInsertId();
} else {
    $cliente_id = $cliente['id_cliente'];
}

/* 3️⃣ Definir Asesor (Manual o Automático) */
if (!empty($user_id)) {
    // Si ya viene un user_id en el API, lo usamos directamente
    $asesor_id = $user_id;
} else {
    // Si viene vacío, buscamos al asesor con menos leads
    $sql = "SELECT l.user_id, COUNT(*) total
            FROM leads l
            INNER JOIN user u ON u.id_user = l.user_id
            INNER JOIN user_role ur ON ur.id_rol = u.rol_id
            WHERE l.cod_emp = ?
            AND ur.activo = 1
            GROUP BY l.user_id
            ORDER BY total ASC
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cod_emp]);
    $asesor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no hay leads registrados aún, buscamos cualquier usuario con rol asesor
    if (!$asesor) {
        $sql = "SELECT u.id_user AS user_id
                FROM user u
                INNER JOIN user_role r ON r.id_rol = u.rol_id
                WHERE (r.nombre_rol LIKE '%asesor%' OR u.rol_id = 2) -- Ajusta el ID según tu BD
                AND u.cod_emp = ?
                AND u.estado = 1 -- Asegurar que el usuario esté activo
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cod_emp]);
        $asesor = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $asesor_id = $asesor ? $asesor['user_id'] : null;
}

// Validar que tengamos un asesor antes de insertar
if (!$asesor_id) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró un asesor disponible']);
    exit;
}

/* 4️⃣ Foco activo */
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
    $asesor_id,
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

echo json_encode([
    'status' => 'ok',
    'lead_id' => $conn->lastInsertId()
]);
