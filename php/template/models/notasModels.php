<?php

class NotasModels
{

    public static function agregarNotas($data)
    {
        $sql = "INSERT INTO nota (tit_nota, desc_not, id_lead) VALUES (?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["tit_not"]);
        $stmt->bindParam(2, $data["desc_not"]);
        $stmt->bindParam(3, $data["id"]);
        if ($stmt->execute()) {
            return $conectar->lastInsertId();
        }

        return "error";
    }

    public static function listarNotasId($id)
    {
        $sql = "SELECT * FROM nota WHERE id_lead = ? ORDER BY fecha_creacion_nota DESC";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
