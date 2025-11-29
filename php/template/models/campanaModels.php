<?php

class CampanaModels
{
    public static function agregarCampana($data)
    {
        $sql = "INSERT INTO campana (nombre, codigo, fecha, id_audiencia) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nombre"]);
        $stmt->bindParam(2, $data["codigo"]);
        $stmt->bindParam(3, $data["fecha"]);
        $stmt->bindParam(4, $data["audiencia"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCampana()
    {
        $sql = "SELECT * FROM campana c INNER JOIN tipo_audiencia t ON c.id_audiencia = t.id_audiencia";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanaId($id)
    {
        $sql = "SELECT * FROM campana c INNER JOIN tipo_audiencia t ON c.id_audiencia = t.id_audiencia WHERE id_campana = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCampana($id)
    {
        $sql = "DELETE FROM campana WHERE id_campana = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
