<?php

class MedioModels
{
    public static function listarMedio()
    {
        $sql = "SELECT * FROM medio";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarMedio($data)
    {
        $sql = "INSERT INTO medio (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_mdo"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarMedioId($id)
    {
        $sql = "SELECT * FROM medio WHERE id_medio = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarMedio($id)
    {
        $sql = "DELETE FROM medio WHERE id_medio = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
