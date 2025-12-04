<?php

class UserModels
{
    public static function listarUser()
    {
        $sql = "SELECT u.*, r.*, GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apellidos)) AS usuario FROM user u INNER JOIN user_role r ON r.id_rol = u.rol_id GROUP BY u.id_user";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function agregarUser($data)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        // 1. Verificar si el usuario ya existe por email o código
        $sqlCheck = "SELECT id_user FROM user WHERE email = ? OR codigo = ?";
        $stmtCheck = $conectar->prepare($sqlCheck);
        $stmtCheck->bindParam(1, $data['correoUser']);
        $stmtCheck->bindParam(2, $data['codigoUser']);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            return "usuario_existente";  // Puedes manejarlo en el frontend
        }

        // 2. Hashear la contraseña
        $passwordHash = password_hash($data['contrasenaUser'], PASSWORD_BCRYPT);

        // 3. Insertar el usuario
        $sql = "INSERT INTO user 
            (codigo, nombres, apellidos, email, telefono, password, rol_id) 
            VALUES (?,?,?,?,?,?,?)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $data['codigoUser']);
        $stmt->bindParam(2, $data['nombreUser']);
        $stmt->bindParam(3, $data['apellidoUser']);
        $stmt->bindParam(4, $data['correoUser']);
        $stmt->bindParam(5, $data['telefonoUser']);
        $stmt->bindParam(6, $passwordHash);   // ← CONTRASEÑA HASHEADA
        $stmt->bindParam(7, $data['rolS']);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }


    public static function listarUserId($id)
    {
        $sql = "SELECT u.*, r.*, GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apellidos)) AS usuario FROM user u INNER JOIN user_role r ON r.id_rol = u.rol_id WHERE u.id_user = ? GROUP BY u.id_user";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function eliminarUser($id)
    {
        $sql = "SELECT * FROM usuario WHERE id_user = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function LoginIngreso($email)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        $sql = "SELECT u.*, r.nombre_rol AS rol_nombre
            FROM user u
            INNER JOIN user_role r ON u.rol_id = r.id_rol
            WHERE email = ?
            LIMIT 1";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
