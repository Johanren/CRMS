<?php

class CiudadModels
{
    public static function listarCiudad()
    {
        $sql = "SELECT * FROM ciudad c INNER JOIN departamento d ON c.id_departamento = d.id_dep";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCiudad($data)
    {
        $sql = "INSERT INTO ciudad (nombre, id_departamento) VALUES (?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_ciu"]);
        $stmt->bindParam(2, $data["id_dep"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCiudadId($id)
    {
        $sql = "SELECT * FROM ciudad WHERE id_ciudad = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCiudad($id)
    {
        $sql = "DELETE FROM ciudad WHERE id_ciudad = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCiudadPorDepartamento($id_dep)
    {
        $sql = "SELECT * FROM ciudad WHERE id_departamento = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_dep]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
