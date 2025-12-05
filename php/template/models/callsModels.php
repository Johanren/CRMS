<?php

class CallsModels
{
    public static function agregarCalls($data)
    {
        $sql = "INSERT INTO calls (desc_call, estado_call, fecha_creacion_call, id_lead, cod_emp, user_id) VALUES (?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nota_call"]);
        $stmt->bindParam(2, $data["estado_call"]);
        $stmt->bindParam(3, $data["fechaSeguimiento"]);
        $stmt->bindParam(4, $data["id"]);
        $stmt->bindParam(5, $_SESSION['cod_emp']);
        $stmt->bindParam(6, $_SESSION['user_id']);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCallsId($id)
    {
        $sql = "SELECT *, u.nombres AS user_name FROM calls c LEFT JOIN user u ON u.id_user = c.user_id WHERE c.id_lead = ? ORDER BY fecha_creacion_call DESC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function actualizarEstadoCall($data)
    {
        $sql = "UPDATE calls SET estado_call = ? WHERE id_calls = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["estado_call"]);
        $stmt->bindParam(2, $data["id_calls"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }
}
