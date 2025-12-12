<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// Cargar controladores y modelos
foreach (glob("../controllers/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

$cliente = new ClienteControllers();
$marketing = new Marketing_trackingControllers();
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        /* Cliente */
        case 'registrar_leads':
            $data = [
                "identificacionLeads" => $_POST["identificacionLeads"] ?? $_POST["cedula"] ?? null,
                "nombresLeads"        => $_POST["nombresLeads"]        ?? $_POST["nombres"] ?? null,
                "apellidosLeads"      => $_POST["apellidosLeads"]      ?? $_POST["apellidos"] ?? null,
                "telefonoLeads"       => $_POST["telefonoLeads"]       ?? $_POST["telefono"] ?? null,
                "correoLeads"         => $_POST["correoLeads"]         ?? $_POST["email"] ?? null,
                "direLeads"           => $_POST["direLeads"]           ?? $_POST["direccion"] ?? null,
                "barrio"              => $_POST["barrio"]              ?? null,
                "ciudad"              => $_POST["ciudad"]              ?? null,
                "origen_url"          => $_POST['origen_url']          ?? null,
                "cod_emp"             => $_POST['cod_emp']             ?? null,
                "sourceField"         => $_POST['sourceField']         ?? null,
                "mediumField"         => $_POST['mediumField']         ?? null,
                "campaignField"       => $_POST['campaignField']       ?? null,
                "ip_usuario"          => $_POST['ip_usuario']          ?? null
            ];

            echo json_encode($cliente->agregarCliente($data));
            break;
        case 'marketing_tracking':
            echo json_encode($marketing->agregarClick($_POST));
            break;
        case 'buscar_ip_tracking':
            echo json_encode($marketing->consultarClickExistete($_POST));
            break;
        default:
            # code...
            break;
    }
}
