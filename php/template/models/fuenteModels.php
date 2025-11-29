<?php

class FuenteModels
{
    public static function listarFuente()
    {
        $sql = "SELECT * FROM fuente";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarFuente($data)
    {
        $sql = "INSERT INTO fuente (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_fnt"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarFuenteId($id)
    {
        $sql = "SELECT * FROM fuente WHERE id_fuente = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarFuente($id)
    {
        $sql = "DELETE FROM fuente WHERE id_fuente = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
