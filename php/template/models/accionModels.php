<?php

class AccionModels
{
    public static function listarAccion()
    {
        $sql = "SELECT * FROM accion";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarAccion($data)
    {
        $sql = "INSERT INTO accion (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_acc"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarAccionId($id)
    {
        $sql = "SELECT * FROM accion WHERE id_accion = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarAccion($id)
    {
        $sql = "DELETE FROM accion WHERE id_accion = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
