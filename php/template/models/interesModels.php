<?php

class InteresModels
{
    public static function listarInteres()
    {
        $sql = "SELECT * FROM interes";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarInteres($data)
    {
        $sql = "INSERT INTO interes (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_ins"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarInteresId($id)
    {
        $sql = "SELECT * FROM interes WHERE id_interes = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarInteres($id)
    {
        $sql = "DELETE FROM interes WHERE id_interes = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
