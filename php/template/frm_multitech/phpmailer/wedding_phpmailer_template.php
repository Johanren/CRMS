<?php
header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("America/Bogota");

// SPAM: si el honeypot viene con algo, salir
if (!empty($_POST['website'])) {
    echo json_encode(["status" => "error", "message" => "Spam detectado"]);
    exit;
}

require_once "../config/conexion.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function loadTemplate($file, $vars = []) {
    $html = file_get_contents(__DIR__ . "/email_templates/" . $file);
    foreach ($vars as $key => $value) {
        $html = str_replace("{{" . $key . "}}", $value, $html);
    }
    return $html;
}

$fecha = date('Y-m-d H:i:s');

$nombres    = $_POST['nombres'] ?? '';
$apellidos  = $_POST['apellidos'] ?? '';
$cedula     = $_POST['cedula'] ?? '';
$email      = $_POST['email'] ?? '';
$telefono   = $_POST['telefono'] ?? '';
$curso      = $_POST['curso'] ?? '';
$origen     = "Página Web Multitech";

// VARIABLES PARA LAS PLANTILLAS
$vars = [
    "NOMBRE" => $nombres,
    "APELLIDOS" => $apellidos,
    "CEDULA" => $cedula,
    "EMAIL" => $email,
    "TELEFONO" => $telefono,
    "CURSO" => $curso,
    "FECHA" => $fecha,
    "ORIGEN" => $origen
];

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";  
    $mail->SMTPAuth = true;
    $mail->Username = "crmdelta@envision.com.co";
    $mail->Password = "r8~N>3tf*n=m";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);
    $mail->setFrom("crmdelta@envision.com.co", "Multitech");

    // 1) ESTUDIANTE
    $mail->clearAllRecipients();
    $mail->addAddress($email, $nombres);
    $mail->Subject = "¡Gracias por tu registro en Multitech!";
    $mail->Body = loadTemplate("estudiante.html", $vars);
    $mail->send();

    // 2) ADMINISTRADOR
    $mail->clearAllRecipients();
    $mail->addAddress("serviciosikeo@gmail.com");
    $mail->Subject = "Nuevo lead registrado – Multitech";
    $mail->Body = loadTemplate("administrador.html", $vars);
    $mail->send();

    // 3) DOCENTE
    $mail->clearAllRecipients();
    $mail->addAddress("veimarcelis@gmail.com");
    $mail->Subject = "Nuevo estudiante interesado en tu curso";
    $mail->Body = loadTemplate("docente.html", $vars);
    $mail->send();

    echo json_encode(["status" => "success", "message" => "Correos enviados correctamente"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error enviando correo: " . $e->getMessage()]);
}

?>
