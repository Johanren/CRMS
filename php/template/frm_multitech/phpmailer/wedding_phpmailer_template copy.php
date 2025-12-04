<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Ansonika">
    <title>Envio de Datos</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style_wedding.css" rel="stylesheet">
	<link href="../css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="../css/custom.css" rel="stylesheet">
    
    
	<script type="text/javascript">
    function delayedRedirect(){
        window.location = "../index.html"
    }
    </script>

</head>
<body onLoad="setTimeout('delayedRedirect()', 8000)" style="background-color:#fff;">
<?php
// SPAM: si el honeypot viene con algo, salir
if (!empty($_POST['website'])) {
    exit('Spam detectado');
}

require_once "../config/conexion.php";
require '../vendor/autoload.php';
date_default_timezone_set('America/Bogota');


// Obtener los datos del formulario 

$nombres = $_POST['firstname'];
$apellidos = $_POST['lastname'];
$cedula = $_POST['cedula'];
$email = $_POST['email'];
$curso = $_POST['curso'];
$telefono = $_POST['telephone'];

$origen = $_POST['origen_url'] ?? '';



// Obtener la fecha y hora actuales en el formato "d/m/Y H:i:s"
$fecha = date('Y-m-d H:i:s');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {

    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp-mail.outlook.com';                           // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'admision@fcuis.edu.co';                             // SMTP username
    $mail->Password   = 'Yuyis123';                             // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients - main edits
    $mail->setFrom('admision@fcuis.edu.co', 'Mensaje de www.fcuis.edu.co Gracias por registrarse');  // Email Address and Name FROM
    $mail->addAddress($email, $nombres);                           // Email Address and Name TO - Name is optional
   // $mail->addReplyTo($correo, $nombre);              // Email Address and Name NOREPLY
    //$mail->addCC('sistemas@fcuis.edu.co');// con copia
    $mail->isHTML(true);                                                       

     $mail->Subject    = '¡Nuevo Lead!'; // Asunto personalizado

    //The email body message


    $message .= "
  <div>
        <p>Estimado <strong>$nombres,</strong></p>
        Gracias por Preinscribirse para ser parte de la <strong>Multitech</strong> <br>
        Estamos comprometidos con el progreso y desarrollo de tu hijo. &iexcl;Cuenta con nosotros! <br>
        Para m&aacute;s informaci&oacute;n, visita: <a href='https://fcuis.edu.co/' target='_blank'>www.fcuis.edu.co</a> <br>
        Te vemos pronto. <br>
        Valor del formulario: <strong>$80.000 Pesos</strong><br>
        Medios de pago: <a href='https://www.avalpaycenter.com/wps/portal/portal-de-pagos/web/pagos-aval/resultado-busqueda/realizar-pago?idConv=00011732&origen=buscar' target='_blank'>Aval Pay Center</a> <br>
        Fundaci&oacute;n Colegio UIS Otros<br>
        Para m&aacute;s detalles de c&oacute;mo realizar proceso del pago, abrir el PDF <a href='https://fcuis.edu.co/web/wp-content/uploads/2021/11/Instructivo-Pago-PIN-Formulario-de-Inscripcion-y-Anexos-2024-1.pdf' target='_blank'>Haciendo clic aqui o sobre la imagen<br>
        <img src='https://fcuis.edu.co/preinscripcion/img/pdf.png' width='100' height='100'></a><br>
        <strong><h3>Por favor, env&iacute;a el comprobante de pago a este correo.</h3></strong><br>
        Una vez recibido el soporte de pago, se le enviar&aacute; al correo electr&oacute;nico registrado el PIN que le permitir&aacute; acceder al formulario de inscripci&oacute;n.<br>
        Nota: Nuestro horario de atenci&oacute;n para env&iacute;o del PIN es el siguiente:
        Lunes a Viernes de 6:30 a.m. a 3:30 p.m.<br>

        <br>
        Saludos cordiales,<br>
        Admisi&oacute;n FCUIS
    </div> ";

        
  

	$mail->Body = "" . $message . "";

    $mail->send();

    // Confirmation/autoreplay email send to who fill the form
    $mail->ClearAddresses();
    $mail->isSMTP();
    //$mail->setFrom('admision@fcuis.edu.co', 'Mensaje de www.fcuis.edu.co');                    // Email Address and Name FROM
    //$mail->addAddress($_POST['email']); // Email address entered on form
    $mail->addAddress($email, $nombres);  
    $mail->isHTML(true);
   
    $mail->Body = "" . $message . "";

   

    // Preparar la consulta SQL
$sql = "INSERT INTO cliente (identificacion, nombres, apellidos, telefono_principal, email,  fecha_creacion) VALUES ( '$cedula', '$nombres', '$apellidos','$telefono' ,'$email', '$fecha')";




if ($conexion->query($sql) === TRUE) {
     // echo "Datos Guardados correctamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

    echo ' <!DOCTYPE html>
<html>
<meta charset="UTF-8">
 <link href="../../../css/bootstrap.min.css" rel="stylesheet">
	<link href="../../../css/menu.css" rel="stylesheet">
    <link href="../../../css/style_wedding.css" rel="stylesheet">
	<link href="../../../css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="../../../css/custom.css" rel="stylesheet">
<body>
    
    
    
    
    <div class="summary">
    <div class="wrapper">
	            <h3>¡Gracias<br>por tu tiempo!</h3>
	            <p>Se ha registrado correctamente</p>
	        </div>
    
    <div id="success">
            <div class="icon icon--order-success svg">
                 <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                  <g fill="none" stroke="#8EC343" stroke-width="2">
                     <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                     <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                  </g>
                 </svg>
             </div>
            <h4><span>¡Solicitud enviada exitosamente!</span></h4>
            <small>Serás redirigido nuevamente en 5 segundos.</small>
        </div>
        
        </div>
        </body>
</html>';
} catch (Exception $e) {
	    echo "No se pudo enviar el mensaje. Error de envíos: {$mail->ErrorInfo}";
        echo "Error: " . $sql . "<br>" . $conexion->error;
	}
	
    // Cerrar la conexión
$conexion->close();
?>
<!-- END SEND MAIL SCRIPT -->   

</body>
</html> 