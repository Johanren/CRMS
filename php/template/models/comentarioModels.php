<?php

class ComentarioModels
{
    public static function listarComentarioId($id)
    {
        $sql = "SELECT c.*, u.nombres AS user_name
            FROM comentario c
            LEFT JOIN user u ON u.id_user = c.user_id
            WHERE c.co_not = ?
            ORDER BY c.fecha_creacion ASC";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarComentario($data)
    {
        $sql = "INSERT INTO comentario (desc_com, co_not, user_id, cod_emp) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["comentario"]);
        $stmt->bindParam(2, $data["nota_id"]);
        $stmt->bindParam(3, $_SESSION['user_id']);
        $stmt->bindParam(4, $_SESSION['cod_emp']);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }
}
