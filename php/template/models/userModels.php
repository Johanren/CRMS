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

    public static function listarUserDetails()
    {
        $sql = "SELECT u.*, r.*, GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apellidos)) AS usuario, e.nom_emp FROM user u INNER JOIN user_role r ON r.id_rol = u.rol_id INNER JOIN empresa e ON e.id_emp = u.cod_emp WHERE u.cod_emp = ? GROUP BY u.id_user";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION['cod_emp']);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function agregarUser($data, $foto)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        /* ================= VALIDAR USUARIO ================= */
        $sqlCheck = "SELECT id_user FROM user WHERE email = ? OR codigo = ?";
        $stmtCheck = $conectar->prepare($sqlCheck);
        $stmtCheck->bindParam(1, $data['correoUser']);
        $stmtCheck->bindParam(2, $data['codigoUser']);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            return "usuario_existente";
        }

        /* ================= PASSWORD ================= */
        $passwordHash = password_hash($data['contrasenaUser'], PASSWORD_BCRYPT);

        /* ================= IMAGEN ================= */
        $rutaFoto = null;

        if (!empty($data['fotoUser']['tmp_name'])) {

            $carpeta = "uploads/usuarios/";

            // Crear carpeta si no existe
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            // Obtener extensión
            $extension = pathinfo($data['fotoUser']['name'], PATHINFO_EXTENSION);

            // Nombre único
            $nombreFoto = "user_" . $data['codigoUser'] . "_" . time() . "." . $extension;

            $rutaFoto = $carpeta . $nombreFoto;

            // Mover imagen
            if (!move_uploaded_file($data['fotoUser']['tmp_name'], $rutaFoto)) {
                return "error_imagen";
            }
        }

        /* ================= INSERT ================= */
        $sql = "INSERT INTO user 
        (codigo, nombres, apellidos, email, telefono, password, rol_id, cod_emp, imagen) 
        VALUES (?,?,?,?,?,?,?,?,?)";

        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data['codigoUser']);
        $stmt->bindParam(2, $data['nombreUser']);
        $stmt->bindParam(3, $data['apellidoUser']);
        $stmt->bindParam(4, $data['correoUser']);
        $stmt->bindParam(5, $data['telefonoUser']);
        $stmt->bindParam(6, $passwordHash);
        $stmt->bindParam(7, $data['rolS']);
        $stmt->bindParam(8, $data['empre']);
        $stmt->bindParam(9, $rutaFoto);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }


    public static function actualizarUser($data, $foto)
    {
        /* ================= PASSWORD ================= */
        if (!empty($data['contrasenaUser'])) {
            $passwordHash = password_hash($data['contrasenaUser'], PASSWORD_BCRYPT);
        } else {
            $passwordHash = $data['contrasenaUserEdit'];
        }

        $conn = new Conexion();
        $conectar = $conn->conectar();

        /* ================= IMAGEN ================= */
        $rutaFoto = $data['fotoUserActual']; // por defecto conserva la actual

        if (!empty($data['fotoUser']['tmp_name'])) {

            $carpeta = "uploads/usuarios/";

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            $extension = pathinfo($data['fotoUser']['name'], PATHINFO_EXTENSION);
            $nombreFoto = "user_" . $data['codigoUser'] . "_" . time() . "." . $extension;
            $rutaNueva = $carpeta . $nombreFoto;

            if (move_uploaded_file($data['fotoUser']['tmp_name'], $rutaNueva)) {

                // Eliminar imagen anterior si existe
                if (!empty($data['fotoActual']) && file_exists($data['fotoActual'])) {
                    unlink($data['fotoActual']);
                }

                $rutaFoto = $rutaNueva;
            }
        }

        /* ================= UPDATE ================= */
        $sql = "UPDATE user SET 
                codigo = ?, 
                nombres = ?, 
                apellidos = ?, 
                email = ?, 
                telefono = ?, 
                password = ?, 
                rol_id = ?, 
                cod_emp = ?, 
                imagen = ?
            WHERE id_user = ?";

        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data['codigoUser']);
        $stmt->bindParam(2, $data['nombreUser']);
        $stmt->bindParam(3, $data['apellidoUser']);
        $stmt->bindParam(4, $data['correoUser']);
        $stmt->bindParam(5, $data['telefonoUser']);
        $stmt->bindParam(6, $passwordHash);
        $stmt->bindParam(7, $data['rolS']);
        $stmt->bindParam(8, $data['empre']);
        $stmt->bindParam(9, $rutaFoto);
        $stmt->bindParam(10, $data['user_id']);

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

        $sql = "SELECT u.*, r.nombre_rol AS rol_nombre, e.foco 
            FROM user u
            INNER JOIN user_role r ON u.rol_id = r.id_rol
            INNER JOIN empresa e ON e.id_emp = u.cod_emp
            WHERE email = ?
            LIMIT 1";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
