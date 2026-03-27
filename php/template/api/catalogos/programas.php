<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$cod_emp = $_GET['cod_emp'];
$conn = (new Conexion())->conectar();

$sql = "SELECT foco FROM empresa WHERE id_emp = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$cod_emp]);

$foco = $stmt->fetchColumn();

$sql = "SELECT cpr_pri, dpr_pri, cho_pri, dho_pri, pri_pri, cup_pri AS cupo FROM prioridad WHERE emp_pri = ? AND foc_pri = ? ORDER BY `prioridad`.`pri_pri` DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$cod_emp, $foco]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
