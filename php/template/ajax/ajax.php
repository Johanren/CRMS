<?php
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
        /*Accion*/
        case 'registrar_est_leads':
            echo json_encode($esta_leads->agregarEstadoLeads($_POST));
            break;
        case 'consultar_est_leads':
            echo json_encode($esta_leads->listarEstadoLeadsId($_POST['id']));
            break;
        case 'eliminar_est_leads':
            echo json_encode($esta_leads->eliminarEstadoLeads($_POST['id']));
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
                    <option value='{$a['id_dep']}'>{$a['nom_dep']}</option>
                ";
            }
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
                    <option value='{$a['id_ciudad']}'>{$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_ciudad_por_departamento':
            $id_dep = $_GET['id_dep'];
            $lista = $ciudad->listarCiudadPorDepartamento($id_dep);

            $option = "<option value=''>Seleccione Ciudad</option>";
            foreach ($lista as $a) {
                $option .= "<option value='{$a['id_ciudad']}'>{$a['nombre']}</option>";
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
                    <option value='{$a['id_barrio']}'>{$a['barrio']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        case 'listar_barrio_por_ciudad':
            $id_ciudad = $_GET['id_ciudad'];
            $lista = $barrio->listarBarrioPorCiudad($id_ciudad);

            $option = "<option value=''>Seleccione Barrio</option>";
            foreach ($lista as $a) {
                $option .= "<option value='{$a['id_barrio']}'>{$a['nombre']}</option>";
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
                    <option value='{$a['id_carrera']}'>{$a['nombre']}</option>
                ";
            }
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
        /*Medio*/
        case 'listar_mdo':
            echo json_encode($medio->listarMedio());
            break;
        case 'listar_mdo_option':
            $lista = $medio->listarMedio();
            $option = "<option value=''>Seleccione Medio</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_medio']}'>{$a['nombre']}</option>
                ";
            }
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
                    <option value='{$a['id_fuente']}'>{$a['nombre']}</option>
                ";
            }
            echo json_encode(["option" => $option]);
            break;
        /*Accion*/
        case 'listar_acc':
            echo json_encode($accion->listarAccion());
            break;
        case 'listar_acc_option':
            $lista = $accion->listarAccion();
            $option = "<option value=''>Seleccione Fuente</option>";
            foreach ($lista as $a) {
                $option .= "
                    <option value='{$a['id_accion']}'>{$a['nombre']}</option>
                ";
            }
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
        default:
            # code...
            break;
    }
}
