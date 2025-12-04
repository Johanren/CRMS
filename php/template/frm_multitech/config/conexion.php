<?php 
require_once "global.php";

// Create connection
$conexion = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}


function tiempo() {
    echo "El tiempo es " . date("h:i:sa d/m/Y  ");
    echo ("<br>");
}

function validarlogin($login,$clave)
{
    global $conexion;
    
    // Consultar la base de datos para validar el inicio de sesión
    $sql = "SELECT * FROM usuarios WHERE usuario='$login' AND clave='$clave'";
    $result = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // El usuario y la contraseña son correctos
        $usuario = mysqli_fetch_assoc($result);
        
        // Iniciar sesión y almacenar el nombre de usuario en una sesión
        session_start();
        $_SESSION["login"] = $usuario["login"];
        
        // Redirigir al usuario a una página de bienvenida
        if ($usuario["acceso"] == '2') {
            // Usuario administrador
            header('Location: menu2.php');
        } else {
            // Usuario normal
            header('Location: menu.php');
        }
        exit; // Detener la ejecución del script después de la redirección
    } else {
        // Las credenciales son incorrectas, mostrar mensaje de error
        echo "Error: Usuario o contraseña incorrectos.";
        echo "<br>";
        echo "<input action='action' onclick='window.history.go(-1); return false;' type='button' value='Reintentar' />";
    }
}