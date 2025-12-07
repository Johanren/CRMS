<?php

class UserModels
{
    public static function listarUser()
    {
        $sql = "SELECT u.*, r.*, GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apellidos)) AS usuario, e.nom_emp FROM user u INNER JOIN user_role r ON r.id_rol = u.rol_id INNER JOIN empresa e ON e.id_emp = u.cod_emp GROUP BY u.id_user";
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
        $sqlCheck = "SELECT id_user FROM user WHERE email = ? OR codigo = ?";
        $stmtCheck = $conectar->prepare($sqlCheck);
        $stmtCheck->bindParam(1, $data['correoUser']);
        $stmtCheck->bindParam(2, $data['codigoUser']);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            return "usuario_existente";
        }
        $passwordHash = password_hash($data['contrasenaUser'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO user 
            (codigo, nombres, apellidos, email, telefono, password, rol_id, cod_emp) 
            VALUES (?,?,?,?,?,?,?,?)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $data['codigoUser']);
        $stmt->bindParam(2, $data['nombreUser']);
        $stmt->bindParam(3, $data['apellidoUser']);
        $stmt->bindParam(4, $data['correoUser']);
        $stmt->bindParam(5, $data['telefonoUser']);
        $stmt->bindParam(6, $passwordHash);
        $stmt->bindParam(7, $data['rolS']);
        $stmt->bindParam(8, $data['empre']);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function actualizarUser($data)
    {
        if (!empty($_POST['contrasenaUser'])) {
            // Si el usuario escribió una nueva contraseña
            $passwordHash = password_hash($_POST['contrasenaUser'], PASSWORD_BCRYPT);
        } else {
            // Si NO escribió nada, usar la contraseña actual (contrasenaUserEdit)
            $passwordHash = $_POST['contrasenaUserEdit'];
        }

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $sql = "UPDATE user SET codigo = ?, nombres = ?, apellidos = ?, email = ?, telefono = ?, password = ?, rol_id = ?, cod_emp = ? WHERE id_user = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $data['codigoUser']);
        $stmt->bindParam(2, $data['nombreUser']);
        $stmt->bindParam(3, $data['apellidoUser']);
        $stmt->bindParam(4, $data['correoUser']);
        $stmt->bindParam(5, $data['telefonoUser']);
        $stmt->bindParam(6, $passwordHash);
        $stmt->bindParam(7, $data['rolS']);
        $stmt->bindParam(8, $data['empre']);
        $stmt->bindParam(9, $data['user_id']);

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
