<?php

class EstadoLeadsModels
{
    public static function listarEstadoLeads()
    {
        $sql = "SELECT * FROM estado_leads";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarEstadoLeads($data)
    {
        $sql = "INSERT INTO estado_leads (nombre) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_est_leads"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarEstadoLeadsId($id)
    {
        $sql = "SELECT * FROM estado_leads WHERE id_estado_leads = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarEstadoLeads($id)
    {
        $sql = "DELETE FROM estado_leads WHERE id_estado_leads = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
