<?php

class CarreraModels
{
    public static function listarCarrera()
    {
        $sql = "SELECT * FROM carrera";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCarrera($data)
    {
        $sql = "INSERT INTO carrera (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_carr"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCarreraId($id)
    {
        $sql = "SELECT * FROM carrera WHERE id_carrera = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCarrera($id)
    {
        $sql = "DELETE FROM carrera WHERE id_carrera = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
