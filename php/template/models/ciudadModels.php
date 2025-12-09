<?php

class CiudadModels
{
    public static function listarCiudad()
    {
        $sql = "SELECT * FROM ciudad c INNER JOIN departamento d ON c.dep_ciu = d.cod_dep WHERE c.dep_ciu = 68 OR c.dep_ciu = 54";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCiudad($data)
    {
        $sql = "INSERT INTO ciudad (desc_ciu, dep_ciu) VALUES (?,?)";
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
        $sql = "SELECT * FROM ciudad WHERE cod_ciu = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCiudad($id)
    {
        $sql = "DELETE FROM ciudad WHERE cod_ciu = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCiudadPorDepartamento($id_dep)
    {
        $sql = "SELECT * FROM ciudad WHERE dep_ciu = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_dep]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
