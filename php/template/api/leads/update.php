<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

$sql = "UPDATE cliente c
INNER JOIN leads l ON l.cliente_id = c.id_cliente
SET 
  c.telefono_principal = ?,
  c.identificacion = ?,
  c.nombres = ?,
  c.apellidos = ?
WHERE l.id_lead = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([
    $data['telefono_principal'],
    $data['identificacion'],
    $data['nombres'],
    $data['apellidos'],
    $data['lead_id']
]);

echo json_encode([
    'status' => 'ok',
    'message' => 'Cliente actualizado'
]);
