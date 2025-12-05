<?php

class NotasModels
{

    public static function agregarNotas($data)
    {
        $sql = "INSERT INTO nota (tit_nota, desc_not, id_lead, user_id, cod_emp) VALUES (?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["tit_not"]);
        $stmt->bindParam(2, $data["desc_not"]);
        $stmt->bindParam(3, $data["id"]);
        $stmt->bindParam(4, $_SESSION['user_id']);
        $stmt->bindParam(5, $_SESSION['cod_emp']);
        if ($stmt->execute()) {
            return $conectar->lastInsertId();
        }

        return "error";
    }

    public static function listarNotasId($id)
    {
        $sql = "SELECT *, u.nombres AS user_name FROM nota n LEFT JOIN user u ON u.id_user = n.user_id WHERE n.id_lead = ? ORDER BY fecha_creacion_nota DESC";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
