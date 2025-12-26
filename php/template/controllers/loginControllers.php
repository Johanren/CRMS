<?php

class LoginControllers
{
    public static function redireccion()
    {
        if (!isset($_SESSION['login'])) {
            return ['redirect' => 'login.php'];
        }
        return ['redirect' => false];
    }

    public static function login($email, $password)
    {

        $user = UserModels::LoginIngreso($email);

        if (!$user) {
            return ["status" => "error"];
        }

        // Verificar hash
        if (password_verify($password, $user["password"])) {

            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user'] = $user['nombres'] ." ".$user['apellidos'];
            $_SESSION['rol'] = $user['rol_nombre'];
            $_SESSION['cod_emp'] = $user['cod_emp'];
            $_SESSION['foco'] = $user['foco'];
            $_SESSION['foto'] = $user['imagen'];

            return [
                "status" => "ok",
                "rol" => $user['rol_nombre']
            ];
        }

        return ["status" => "error"];
    }
}
