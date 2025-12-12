<?php

class MotivoModels
{
    public static function registrarMotivo($data) {
        $sql = "INSERT INTO motivo_estado_leads (desc_mot, eslead_mot) VALUES (?, 7)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $data['desc_not']);
        $stmt->execute();
        return $conectar->lastInsertId();
    }

    public static function listarMotivos($id){
        $sql = "SELECT *, u.nombres AS autor FROM `leads` l INNER JOIN motivo_estado_leads m ON m.id_mot = l.est_motivo INNER JOIN user u ON u.id_user = l.user_id WHERE l.id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
