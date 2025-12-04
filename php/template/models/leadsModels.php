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
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, ci.desc_ciu AS ciudad
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id";
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
        $stmt->execute([$idEstado, $idLead]);
        return "ok";
    }

    public static function listarLeads()
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, p.desc_pro, ci.desc_ciu AS ciudad, l.fecha_creacion FROM leads l INNER JOIN cliente c ON c.id_cliente = l.cliente_id INNER JOIN programa p ON p.cod_pro = l.carrera_id LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id LEFT JOIN user u ON u.id_user = l.user_id";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function listarLeadsId($id)
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, p.desc_pro, ci.desc_ciu AS ciudad, l.fecha_creacion, f.desc_fue, c.direccion, c.identificacion, el.nombre AS estado_leads, es.des_seguimiento, pro.desc_pro, pro.val_pro, em.nom_emp FROM leads l LEFT JOIN cliente c ON c.id_cliente = l.cliente_id LEFT JOIN programa p ON p.cod_pro = l.carrera_id LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id LEFT JOIN user u ON u.id_user = l.user_id LEFT JOIN fuente1 f ON f.cod_fue = l.fuente_id LEFT JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id LEFT JOIN estado_seguimiento es ON es.id_seguimiento = l.estadoLeadsSeguimiento LEFT JOIN programa pro ON pro.cod_pro = l.carrera_id LEFT JOIN empresa em ON em.id_emp = pro.emp_pro WHERE l.id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
