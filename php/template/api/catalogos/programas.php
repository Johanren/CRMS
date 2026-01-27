<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$cod_emp = $_GET['cod_emp'];
$conn = (new Conexion())->conectar();

$sql = "SELECT cod_pro, desc_pro FROM programa WHERE emp_pro = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$cod_emp]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
