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

$campana = new CampanaControllers();
$audiotira = new AuditoriaControllers();
$departamento = new DepartamentoControllers();
$ciudad = new CiudadControllers();
$barrio = new BarrioControllers();
$carrera = new CarreraControllers();
$horario = new HorarioControllers();
$interes = new InteresControllers();
$medio = new MedioControllers();
$fuente = new FuenteControllers();
$accion = new AccionControllers();
$esta_leads = new EstadoLeadsControllersControllers();
$cliente = new ClienteControllers();
$leads = new LeadsControllers();
$notas = new NotasControllers();
$comentario = new ComentarioControllers();
$calls = new CallsControlellers();
$proximaActividad = new ProximaActividadControllers();
$rol = new RolControllers();
$user = new UserControllers();
$login = new LoginControllers();
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        /*Campana*/
        case 'registrar_campana':
            echo json_encode($campana->agregarCampana($_POST));
            break;
        case 'consultar_campana':
            echo json_encode($campana->listarCampanaId($_POST['id']));
            break;
        case 'eliminar_campana':
            echo json_encode($campana->eliminarCampana($_POST['id']));
            break;
        /*auditoria*/
        /*departamento*/
        case 'registrar_Depart':
            echo json_encode($departamento->agregarDepartamento($_POST));
            break;
        case 'consultar_depar':
            echo json_encode($departamento->listarDepartamentoId($_POST['id']));
            break;
        case 'eliminar_depar':
            echo json_encode($departamento->eliminarDepartamento($_POST['id']));
            break;
        /*Ciudad*/
        case 'registrar_ciudad':
            echo json_encode($ciudad->agregarCiudad($_POST));
            break;
        case 'consultar_ciu':
            echo json_encode($ciudad->listarCiudadId($_POST['id']));
            break;
        case 'eliminar_ciu':
            echo json_encode($ciudad->eliminarCiudad($_POST['id']));
            break;
        /*Barrio*/
        case 'registrar_barrio':
            echo json_encode($barrio->agregarBarrio($_POST));
            break;
        case 'consultar_brr':
            echo json_encode($barrio->listarBarrioId($_POST['id']));
            break;
        case 'eliminar_brr':
            echo json_encode($barrio->eliminarBarrio($_POST['id']));
            break;
        /*Carrera*/
        case 'registrar_carr':
            echo json_encode($carrera->agregarCarrera($_POST));
            break;
        case 'consultar_carr':
            echo json_encode($carrera->listarCarreraId($_POST['id']));
            break;
        case 'eliminar_carr':
            echo json_encode($carrera->eliminarCarrera($_POST['id']));
            break;
        /*Horario*/
        case 'registrar_hrs':
            echo json_encode($horario->agregarHorario($_POST));
            break;
        case 'consultar_hrs':
            echo json_encode($horario->listarHorarioId($_POST['id']));
            break;
        case 'eliminar_hrs':
            echo json_encode($horario->eliminarHorario($_POST['id']));
            break;
        /*Interes*/
        case 'registrar_ins':
            echo json_encode($interes->agregarInteres($_POST));
            break;
        case 'consultar_ins':
            echo json_encode($interes->listarInteresId($_POST['id']));
            break;
        case 'eliminar_ins':
            echo json_encode($interes->eliminarInteres($_POST['id']));
            break;
        /*Medio*/
        case 'registrar_mdo':
            echo json_encode($medio->agregarMedio($_POST));
            break;
        case 'consultar_mdo':
            echo json_encode($medio->listarMedioId($_POST['id']));
            break;
        case 'eliminar_mdo':
            echo json_encode($medio->eliminarMedio($_POST['id']));
            break;
        /*Medio*/
        case 'registrar_fnt':
            echo json_encode($fuente->agregarFuente($_POST));
            break;
        case 'consultar_fnt':
            echo json_encode($fuente->listarFuenteId($_POST['id']));
            break;
        case 'eliminar_fnt':
            echo json_encode($fuente->eliminarFuente($_POST['id']));
            break;
        /*Accion*/
        case 'registrar_acc':
            echo json_encode($accion->agregarAccion($_POST));
            break;
        case 'consultar_acc':
            echo json_encode($accion->listarAccionId($_POST['id']));
            break;
        case 'eliminar_acc':
            echo json_encode($accion->eliminarAccion($_POST['id']));
            break;
        /*Estado leads*/
        case 'registrar_est_leads':
            echo json_encode($esta_leads->agregarEstadoLeads($_POST));
            break;
        case 'consultar_est_leads':
            echo json_encode($esta_leads->listarEstadoLeadsId($_POST['id']));
            break;
        case 'eliminar_est_leads':
            echo json_encode($esta_leads->eliminarEstadoLeads($_POST['id']));
            break;
        /* Cliente */
        case 'registrar_leads':
            echo json_encode($cliente->agregarCliente($_POST));
            break;
        case 'actualizar_leads':
            echo json_encode($cliente->actualizarCliente($_POST));
            break;
        /*LEADS */
        case 'updateEstado':
            echo json_encode($leads->updateEstado($_POST["id_lead"], $_POST["id_estado"]));
            break;
        case 'listar_leads_id':
            echo json_encode($leads->listarLeadsId($_POST['id']));
            break;
        case 'consultar_leads':
            echo json_encode($leads->listarLeadsId($_POST['id']));
            break;
        case 'cambiar_asesor':
            $id_lead = $_POST["id_lead"];
            $nuevo_user_id = $_POST["nuevo_user_id"];
            echo $leads->cambiarAsesor($id_lead, $nuevo_user_id);
            break;
        case "update_field":

            $leadId = $_POST["lead_id"];
            $columna = $_POST["column"];
            $especiales = [
                "observaciones_id" => "observaciones",
                "empresaCarrera_id" => "cod_emp",
                "nombreClientes_id" => "nombres",
                "apellidoClientes_id" => "apellidos",
                "direccionClientes_id" => "direccion"
            ];
            if (isset($especiales[$columna])) {
                $columna = $especiales[$columna];
            }
            $valor  = $_POST["value"];

            $response = $leads->actualizarColumnasLeads($leadId, $columna, $valor);

            echo json_encode(["status" => "ok", "data" => $response]);
            break;
        /*Notas */
        case 'registrar_notas':
            echo json_encode($notas->agregarNotas($_POST));
            break;
        /*Comentario */
        case 'agregar_comentario':
            echo json_encode($comentario->agregarComentario($_POST));
            break;
        /*Calls */
        case 'registrar_calls':
            echo json_encode($calls->agregarCalls($_POST));
            break;
        case 'listar_llamadas':
            echo json_encode($calls->listarCallsId($_POST['id_lead']));
            break;
        case 'actualizar_estado_call':
            echo json_encode($calls->actualizarEstadoCall($_POST));
            break;
        /*Proxima Actividad */
        case 'registrar_actividad_pro':
            echo json_encode($proximaActividad->agregarProximaActividad($_POST));
            break;
        case 'listar_proximas_actividades':
            echo json_encode($proximaActividad->listarProximaActividadId($_POST["id_lead"]));
            break;
        /*ROL */
        case 'registrar_rol':
            echo json_encode($rol->agregarRol($_POST));
            break;
        case 'consultar_rol':
            echo json_encode($rol->listarRolId($_POST['id']));
            break;
        case 'eliminar_rol':
            echo json_encode($rol->eliminarRol($_POST['id']));
            break;
        /*USER */
        case 'registrar_user':
            echo json_encode($user->agregarUser($_POST));
            break;
        case 'actualizar_user':
            echo json_encode($user->actualizarUser($_POST));
            break;
        case 'consultar_user':
            echo json_encode($user->listarUserId($_POST['id']));
            break;
        case 'eliminar_user':
            echo json_encode($user->eliminarUser($_POST['id']));
            break;
        /*LOGIN */
        case "login":
            $email = $_POST['email'];
            $password = $_POST['password'];
            $respuesta = $login->login($email, $password);
            echo json_encode($respuesta);
            break;
        default:
            # code...
            break;
    }
}

/*Sentencia GET*/
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        /*Campana*/
        case 'listar_campanas':
            echo json_encode($campana->listarCampana());
            break;
        case 'listar_campana_option':
            $lista = $campana->listarCampana();
            $option = "<option value=''>Seleccione Campaña</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_campana']}'>{$a['codigo']} {$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_campana_li':
            $lista = $campana->listarCampana();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-campana' value='{$a['id_campana']}' type='checkbox'>
                        {$a['codigo']} {$a['nombre']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*auditoria*/
        case 'listar_auditoria':
            $lista = $audiotira->listarAuditoria();
            $option = "<option value=''>Seleccione Audiencia</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_audiencia']}'>{$a['tipo_audiencia']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        /*Departamento*/
        case 'listar_depar':
            echo json_encode($departamento->listarDepartamento());
            break;
        case 'listar_depar_option':
            $lista = $departamento->listarDepartamento();
            $option = "<option value=''>Seleccione Departamento</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['cod_dep']}'>{$a['desc_dep']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_depar_ul':
            $lista = $departamento->listarDepartamento();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-dep' value='{$a['cod_dep']}' type='checkbox'>
                        {$a['desc_dep']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Ciudad*/
        case 'listar_ciudad':
            echo json_encode($ciudad->listarCiudad());
            break;
        case 'listar_ciudad_option':
            $lista = $ciudad->listarCiudad();
            $option = "<option value=''>Seleccione Ciudad</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['cod_ciu']}'>{$a['desc_ciu']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_ciudad_ul':
            $lista = $ciudad->listarCiudad();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-ciu' value='{$a['cod_ciu']}' type='checkbox'>
                        {$a['desc_ciu']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        case 'listar_ciudad_por_departamento':
            $id_dep = $_GET['id_dep'];
            $lista = $ciudad->listarCiudadPorDepartamento($id_dep);

            $option = "<option value=''>Seleccione Ciudad</option>";
            foreach ($lista as $a) {
                $option .= "<option value='{$a['cod_ciu']}'>{$a['desc_ciu']}</option>";
            }

            echo json_encode(["option" => $option]);
            break;

        /*Barrio*/
        case 'listar_barrio':
            echo json_encode($barrio->listarBarrio());
            break;
        case 'listar_barrio_option':
            $lista = $barrio->listarBarrio();
            $option = "<option value=''>Seleccione Barrio</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_barrio']}'>{$a['desc_brr']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_barrio_ul':
            $lista = $barrio->listarBarrio();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-brr' value='{$a['id_barrio']}' type='checkbox'>
                        {$a['desc_brr']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        case 'listar_barrio_por_ciudad':
            $id_ciudad = $_GET['id_ciudad'];
            $lista = $barrio->listarBarrioPorCiudad($id_ciudad);

            $option = "<option value=''>Seleccione Barrio</option>";
            foreach ($lista as $a) {
                $option .= "<option value='{$a['id_barrio']}'>{$a['desc_brr']}</option>";
            }

            echo json_encode(["option" => $option]);
            break;

        /*Carrera*/
        case 'listar_carr':
            echo json_encode($carrera->listarCarrera());
            break;
        case 'listar_carrera_option':
            $lista = $carrera->listarCarrera();
            $option = "<option value=''>Seleccione Carrera</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['cod_pro']}'>{$a['desc_pro']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_carrera_ul':
            $lista = $carrera->listarCarrera();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-carrera' value='{$a['desc_pro']}' type='checkbox'>
                        {$a['desc_pro']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Horario*/
        case 'listar_hrs':
            echo json_encode($horario->listarHorario());
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
        case 'listar_hrs_ul':
            $lista = $horario->listarHorario();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-horario' value='{$a['id_horario']}' type='checkbox'>
                        {$a['descripcion']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Interes*/
        case 'listar_ins':
            echo json_encode($interes->listarInteres());
            break;
        case 'listar_ins_option':
            $lista = $interes->listarInteres();
            $option = "<option value=''>Seleccione Interes</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_interes']}'>{$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_ins_ul':
            $lista = $interes->listarInteres();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-interes' value='{$a['id_interes']}' type='checkbox'>
                        {$a['nombre']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Medio*/
        case 'listar_mdo':
            echo json_encode($medio->listarMedio());
            break;
        case 'listar_mdo_option':
            $lista = $medio->listarMedio();
            $option = "<option value=''>Seleccione Medio</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['cod_med']}'>{$a['desc_med']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_mdo_ul':
            $lista = $medio->listarMedio();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-medio' value='{$a['cod_med']}' type='checkbox'>
                        {$a['desc_med']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Fuente*/
        case 'listar_fnt':
            echo json_encode($fuente->listarFuente());
            break;
        case 'listar_fnt_option':
            $lista = $fuente->listarFuente();
            $option = "<option value=''>Seleccione Fuente</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['cod_fue']}'>{$a['desc_fue']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_fnt_ul':
            $lista = $fuente->listarFuente();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-fuente' value='{$a['cod_fue']}' type='checkbox'>
                        {$a['desc_fue']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        case 'listar_fuente_por_medio':
            $id_med = $_GET['id_med'];
            $lista = $fuente->listarFuentePorMedio($id_med);

            $option = "<option value=''>Seleccione Fuento</option>";
            foreach ($lista as $a) {
                $option .= "<option value='{$a['cod_fue']}'>{$a['desc_fue']}</option>";
            }

            echo json_encode(["option" => $option]);
            break;
        /*Accion*/
        case 'listar_acc':
            echo json_encode($accion->listarAccion());
            break;
        case 'listar_acc_option':
            $lista = $accion->listarAccion();
            $option = "<option value=''>Seleccione Acción</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_accion']}'>{$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_acc_ul':
            $lista = $accion->listarAccion();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                <li>
                    <label class='dropdown-item px-2 d-flex align-items-center'>
                    <input class='form-check-input m-0 me-1 filtro filtro-accion' value='{$a['id_accion']}' type='checkbox'>
                        {$a['nombre']}
                    </label>
                </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        /*Estado Leads*/
        case 'listar_est_leads':
            echo json_encode($esta_leads->listarEstadoLeads());
            break;
        case 'listar_est_leads_option':
            $lista = $esta_leads->listarEstadoLeads();
            $option = "<option value=''>Seleccione Estado Leads</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_estado_leads']}'>{$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_est_leads_ul':
            $lista = $esta_leads->listarEstadoLeads();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                    <li>
                        <label class='dropdown-item px-2 d-flex align-items-center'>
                        <input class='form-check-input m-0 me-1 filtro filtro-estado' value='{$a['nombre']}' type='checkbox'>
                            {$a['nombre']}
                        </label>
                    </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        case 'getEstados':
            echo json_encode($esta_leads->getEstados());
            break;
        /*LEADS */
        case 'getLeads':
            $texto = $_GET['texto'] ?? '';
            $asesor = isset($_GET['asesor']) ? json_decode($_GET['asesor']) : [];
            $carreras = isset($_GET['carreras']) ? json_decode($_GET['carreras']) : [];
            $horario = isset($_GET['horario']) ? json_decode($_GET['horario']) : [];
            $interes = isset($_GET['interes']) ? json_decode($_GET['interes']) : [];
            $medio = isset($_GET['medio']) ? json_decode($_GET['medio']) : [];
            $fuente = isset($_GET['fuente']) ? json_decode($_GET['fuente']) : [];
            $campana = isset($_GET['campana']) ? json_decode($_GET['campana']) : [];
            $accion = isset($_GET['accion']) ? json_decode($_GET['accion']) : [];
            $departamento = isset($_GET['departamento']) ? json_decode($_GET['departamento']) : [];
            $ciudad = isset($_GET['ciudad']) ? json_decode($_GET['ciudad']) : [];
            $barrio = isset($_GET['barrio']) ? json_decode($_GET['barrio']) : [];
            $estados = isset($_GET['estados']) ? json_decode($_GET['estados']) : [];
            echo json_encode($leads->getLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados));
            break;
        case 'listar_leads':
            $texto = $_GET['texto'] ?? '';
            $asesor = isset($_GET['asesor']) ? json_decode($_GET['asesor']) : [];
            $carreras = isset($_GET['carreras']) ? json_decode($_GET['carreras']) : [];
            $horario = isset($_GET['horario']) ? json_decode($_GET['horario']) : [];
            $interes = isset($_GET['interes']) ? json_decode($_GET['interes']) : [];
            $medio = isset($_GET['medio']) ? json_decode($_GET['medio']) : [];
            $fuente = isset($_GET['fuente']) ? json_decode($_GET['fuente']) : [];
            $campana = isset($_GET['campana']) ? json_decode($_GET['campana']) : [];
            $accion = isset($_GET['accion']) ? json_decode($_GET['accion']) : [];
            $departamento = isset($_GET['departamento']) ? json_decode($_GET['departamento']) : [];
            $ciudad = isset($_GET['ciudad']) ? json_decode($_GET['ciudad']) : [];
            $barrio = isset($_GET['barrio']) ? json_decode($_GET['barrio']) : [];
            $estados = isset($_GET['estados']) ? json_decode($_GET['estados']) : [];
            echo json_encode($leads->listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados));
            break;

        /*Notas */
        case 'listarNotas':
            echo json_encode($notas->listarNotasId($_GET['id']));
            break;
        /*ROL */
        case 'listar_rol':
            echo json_encode($rol->listarRol());
            break;
        case 'listar_rol_option':
            $lista = $rol->listarRol();
            $option = "<option value=''>Seleccione Rol</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_rol']}'>{$a['nombre_rol']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        /*USER */
        case 'listar_user':
            echo json_encode($user->listarUser());
            break;
        case 'listar_user_option':
            $lista = $user->listarUser();
            $option = "<option value=''>Seleccione Usuerio</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_user']}'>{$a['nombres']} {$a['apellidos']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_user_ul':
            $lista = $user->listarUser();
            $option = "<ul>";
            foreach ($lista as $a) {
                $option .= "
                    <li>
                        <label class='dropdown-item px-2 d-flex align-items-center'>
                        <input class='form-check-input m-0 me-1 filtro filtro-asesor' value='{$a['id_user']}' type='checkbox'>
                            {$a['nombres']} {$a['apellidos']}
                        </label>
                    </li>
                ";
            }
            $option .= "</ul>";
            echo json_encode(["option" => $option]);
            break;
        case 'listar_user_option_leads':
            echo json_encode($user->listarUserDetails());
            break;
        /*LOGIN */
        case 'redireccionamiento':
            echo json_encode(LoginControllers::redireccion());
            break;
        default:
    }
}
