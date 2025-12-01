<?php

class LeadsModels
{
    public static function agregarLeads($data, $id, $id_user, $id_estado_leads)
    {
        $sql = "INSERT INTO leads (user_id, cliente_id, info_adicional, carrera_id, horario_id, interes_id, fuente_id, campana_id, accion_id, departamento_id, barrio_id, ciudad_id, estado_leads_id, observaciones) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $id);
        $stmt->bindParam(3, $data["infoLeads"]);
        $stmt->bindParam(4, $data["carrera"]);
        $stmt->bindParam(5, $data["horario"]);
        $stmt->bindParam(6, $data["interes"]);
        $stmt->bindParam(7, $data["fuente"]);
        $stmt->bindParam(8, $data["campana"]);
        $stmt->bindParam(9, $data["accion"]);
        $stmt->bindParam(10, $data["departamento"]);
        $stmt->bindParam(11, $data["barrio"]);
        $stmt->bindParam(12, $data["ciudad"]);
        $stmt->bindParam(13, $id_estado_leads);
        $stmt->bindParam(14, $data["observacionLeads"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function getLeads()
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateEstado($idLead, $idEstado)
    {
        $sql = "UPDATE leads SET estado_leads_id = ? WHERE id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        return $stmt->execute([$idEstado, $idLead]);
    }

    public static function listarLeads()
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, p.nombre AS programa, l.fecha_creacion FROM leads l INNER JOIN cliente c ON c.id_cliente = l.cliente_id INNER JOIN carrera p ON p.id_carrera = l.carrera_id LEFT JOIN user u ON u.id_user = l.user_id";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
