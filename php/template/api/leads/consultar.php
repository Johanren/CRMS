<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

$where = [];
$params = [];

// Si viene identificación
if (!empty($data['identificacion'])) {
    $where[] = "identificacion = ?";
    $params[] = $data['identificacion'];
}

// Si viene teléfono
if (!empty($data['telefono_principal'])) {
    $where[] = "telefono_principal = ?";
    $params[] = $data['telefono_principal'];
}

// Validación: al menos uno debe venir
if (empty($where)) {
    echo json_encode([
        'error' => true,
        'message' => 'Debe enviar identificación o teléfono'
    ]);
    exit;
}

// Armar SQL dinámico
$sql = "SELECT id_cliente, nombres, apellidos, telefono_principal, identificacion
        FROM cliente
        WHERE " . implode(" OR ", $where) . "
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
