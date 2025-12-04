<?php

class RolModels{
    public static function listarRol(){
        $sql = "SELECT * FROM user_role";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function agregarRol($data){
        $sql = "INSERT INTO user_role (nombre_rol) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["rol"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarRolId($id){
        $sql = "SELECT * FROM user_role WHERE id_rol = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function eliminarRol($id){
        $sql = "DELETE FROM user_role WHERE id_rol = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }
}