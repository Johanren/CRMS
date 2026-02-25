<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

$where = [];
$params = [];

/* ===== NORMALIZADOR ===== */
function normalizarTelefono($telefono) {
    $telefono = preg_replace('/\D/', '', $telefono);
    return (substr($telefono, 0, 2) === '57')
        ? substr($telefono, 2)
        : $telefono;
}

/* ===== IDENTIFICACIÓN (LIKE) ===== */
if (!empty($data['identificacion'])) {
    $where[] = "c.identificacion LIKE ?";
    $params[] = "%" . trim($data['identificacion']) . "%";
}

/* ===== TELÉFONO (LIKE + NORMALIZADO) ===== */
if (!empty($data['telefono_principal'])) {

    $telefono = normalizarTelefono($data['telefono_principal']);

    $where[] = "c.telefono_principal LIKE ?";
    $params[] = "%" . $telefono . "%";
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
$sql = "SELECT l.id_lead, c.id_cliente, c.nombres, c.apellidos, 
        c.telefono_principal, c.identificacion FROM `leads` l 
        INNER JOIN cliente c ON c.id_cliente = l.cliente_id
        WHERE " . implode(" OR ", $where) . "
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
