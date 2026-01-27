<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$id_emp = $_GET['id_emp'];
$conn = (new Conexion())->conectar();

$sql = "SELECT foco FROM empresa WHERE id_emp = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_emp]);

echo json_encode([
    'foco' => $stmt->fetchColumn()
]);
