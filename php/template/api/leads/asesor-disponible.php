<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$cod_emp = $data['cod_emp'];
$conn = (new Conexion())->conectar();

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
$res = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    $sql = "SELECT u.id_user AS user_id
            FROM user u
            INNER JOIN user_role r ON r.id_rol = u.rol_id
            WHERE r.nombre_rol LIKE '%asesor%'
            AND u.cod_emp = ?
            AND r.activo = 1
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cod_emp]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
}

echo json_encode($res);
