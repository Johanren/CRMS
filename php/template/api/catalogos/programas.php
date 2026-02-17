<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$cod_emp = $_GET['cod_emp'];
$prio = $_GET['dis_pro'];
$conn = (new Conexion())->conectar();

$sql = "SELECT cpr_pri, dpr_pri FROM prioridad WHERE emp_pri = ? AND pri_pri = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$cod_emp, $prio]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
