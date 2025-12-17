<?php

class NotificacionesModels
{

    public static function crearNotificacion($data)
    {
        $sql = "INSERT INTO notificaciones 
                (user_id, titulo, mensaje, modulo, referencia)
                VALUES (:user_id, :titulo, :mensaje, :modulo, :referencia)";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        return $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':titulo'     => $data['titulo'],
            ':mensaje'    => $data['mensaje'],
            ':modulo'     => $data['modulo'],
            ':referencia' => $data['referencia']
        ]);
    }

    public static function listarTodas()
    {
        $sql = "SELECT * FROM notificaciones ORDER BY fecha DESC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorUsuario($user_id, $limit = null)
    {
        $sql = "SELECT * FROM notificaciones WHERE user_id = :user_id ORDER BY fecha DESC";
        if ($limit) $sql .= " LIMIT $limit";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarLeida($id)
    {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id_notificacion = :id";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function contarNoLeidas($user_id = null)
    {
        if ($user_id) {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE leida = 0 AND user_id = :user_id";
            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
        } else {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE leida = 0";
            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
