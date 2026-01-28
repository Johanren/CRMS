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
$carrera = new CarreraControllers();
$horario = new HorarioControllers();
$leads = new LeadsControllers();
$user = new UserControllers();
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
                "ip_usuario"          => $_POST['ip_usuario']          ?? null,
                'carrera'             => $_POST['carrera']             ?? null
            ];

            echo json_encode($cliente->agregarCliente($data));
            break;
        case 'marketing_tracking':
            echo json_encode($marketing->agregarClick($_POST));
            break;
        case 'buscar_ip_tracking':
            echo json_encode($marketing->consultarClickExistete($_POST));
            break;
        case 'buscar_cliente_leads':
            $valor = trim($_POST["valor"]);
            echo json_encode($leads->consultarClienteLeads($valor));
            break;
        case 'actualizar_lead':
            $leads->actualizarLeadCompleto('');
            break;
        default:
            # code...
            break;
    }
} else {
    switch ($_GET['accion']) {
        case 'listar_carrera_option':
            $lista = $carrera->listarCarrera();
            $option = "<option value=''>Seleccione Carrera</option>";
            foreach ($lista as $a) {
                if ($a['desc_pro'] == 'GENERAL') {
                    # code...
                } else {
                    $option .= "
                    <option value='{$a['cod_pro']}'>{$a['desc_pro']}</option>
                ";
                }
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_hrs_option':
            $lista = $horario->listarHorario();
            $option = "<option value=''>Seleccione Horario</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_horario']}'>{$a['descripcion']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_user_option':
            $lista = $user->listarUser();
            $option = "<option value=''>Seleccione Usuario</option>";
            foreach ($lista as $a) {
                if ($a['rol_id'] == 5) {

                    $option .= "
                    <option value='{$a['id_user']}'>{$a['nombres']} {$a['apellidos']}</option>
                ";
                }
            }
            echo json_encode(["option" => $option]);
            break;
        case 'reporte_rst_frm':

            $texto = $_GET['texto'] ?? '';
            $asesor = isset($_GET['asesor']) ? json_decode($_GET['asesor']) : [];

            echo json_encode($leads->listarReporteRst($texto, $asesor));
            break;
        case 'rst_frm_dia':
            $mes  = date('m');
            $anio = date('Y');

            echo json_encode($leads->listarReporteRstDia($mes, $anio));
            break;
        default:
            # code...
            break;
    }
}
