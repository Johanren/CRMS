<?php

class FiltroModels
{
    public static function agregarFiltro($id_user, $nombre, $filtro)
    {
        $sql = "INSERT INTO filtros_usuarios (usuario_id, nombre, filtros)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE filtros = ?, fecha_actualizacion = NOW()";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $nombre);
        $stmt->bindParam(3, $filtro);
        $stmt->bindParam(4, $filtro);
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Filtros guardados'];
        }

        return "error";
    }

    public static function cargarFiltro($id_user, $nombre)
    {
        $sql = "SELECT filtros FROM filtros_usuarios WHERE usuario_id = ? AND nombre = ? LIMIT 1";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $nombre);
        $stmt->execute();
        $filtros = $stmt->fetchColumn();

        if ($filtros) {
            return json_decode($filtros, true); // convertir string JSON a array asociativo
        } else {
            return null;
        }
    }
}
