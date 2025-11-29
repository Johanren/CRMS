<?php

class HorarioModels
{
    public static function listarHorario()
    {
        $sql = "SELECT * FROM horario";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarHorario($data)
    {
        $sql = "INSERT INTO horario (descripcion) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_hrs"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarHorarioId($id)
    {
        $sql = "SELECT * FROM horario WHERE id_horario = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarHorario($id)
    {
        $sql = "DELETE FROM horario WHERE id_horario = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
