<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

$sql = "SELECT id_cliente, nombres, apellidos, telefono_principal, identificacion
        FROM cliente
        WHERE identificacion = ? OR telefono_principal = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    $data['identificacion'] ?? '',
    $data['telefono_principal'] ?? ''
]);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
