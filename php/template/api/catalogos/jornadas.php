<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$conn = (new Conexion())->conectar();

$sql = "SELECT cod_jor, desc_jor FROM jornada";
$stmt = $conn->prepare($sql);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
