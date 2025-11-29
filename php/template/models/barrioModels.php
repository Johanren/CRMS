<?php

class BarrioModels
{
    public static function listarBarrio()
    {
        $sql = "SELECT *, b.nombre AS barrio FROM barrio b INNER JOIN ciudad c ON c.id_departamento = b.ciudad_id INNER JOIN departamento d ON c.id_departamento = d.id_dep";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarBarrio($data)
    {
        $sql = "INSERT INTO barrio (nombre, ciudad_id) VALUES (?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_brr"]);
        $stmt->bindParam(2, $data["id_ciu"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarBarrioId($id)
    {
        $sql = "SELECT * FROM barrio WHERE id_barrio = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarBarrio($id)
    {
        $sql = "DELETE FROM barrio WHERE id_barrio = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarBarrioPorCiudad($id_ciudad)
    {
    
        $sql = "SELECT * FROM barrio WHERE ciudad_id = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id_ciudad]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
