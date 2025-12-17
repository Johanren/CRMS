<?php

class ProximaActividadModels
{
    public static function agregarProximaActividad($data)
    {
        $sql = "INSERT INTO proxima_actividad (tit_act, desc_act, recor_act, prio_act, id_lead, cod_emp, id_user) VALUES (?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["tituloProAct"]);
        $stmt->bindParam(2, $data["descProAct"]);
        $stmt->bindParam(3, $data["recor_act"]);
        $stmt->bindParam(4, $data["prio_act"]);
        $stmt->bindParam(5, $data["id"]);
        $stmt->bindParam(6, $_SESSION['cod_emp']);
        $stmt->bindParam(7, $_SESSION['user_id']);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarProximaActividadId($id)
    {
        $sql = "SELECT *, u.nombres AS user_name FROM proxima_actividad p LEFT JOIN user u ON u.id_user = p.id_user WHERE p.id_lead = ? ORDER BY fecha_creacion_actividad_proxima DESC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function listarProximaActividad($id, $inicio = null, $fin = null)
    {
        $sql = "SELECT 
                tit_act,
                desc_act,
                prio_act,
                DATE(fecha_creacion_actividad_proxima) AS fecha
            FROM proxima_actividad
            WHERE id_user = ?";

        if ($inicio && $fin) {
            $sql .= " AND DATE(fecha_creacion_actividad_proxima) BETWEEN ? AND ?";
        }

        $sql .= " ORDER BY fecha_creacion_actividad_proxima DESC";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        if ($inicio && $fin) {
            $stmt->bindParam(2, $inicio);
            $stmt->bindParam(3, $fin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
