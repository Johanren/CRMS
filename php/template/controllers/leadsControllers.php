<?php

class LeadsControllers
{
    public static function agregarLeads($data, $id_cliente)
    {
        // 1️⃣ Usuario / asesor
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id) {
            $asesor = LeadsModels::obtenerAsesorConMenosLeads($data);
            $user_id = $asesor['user_id'];
        }

        // 2️⃣ Estado
        $estado = (!empty($data['desc_not']) || !empty($data['desc_arch'])) ? 2 : 1;

        // 3️⃣ Crear Lead
        $id_lead = LeadsModels::agregarLeads($data, $id_cliente, $user_id, $estado);

        if (!$id_lead) {
            return "error";
        }

        // 4️⃣ Crear Nota (si aplica)
        if (!empty($data['desc_not']) || !empty($data['desc_arch'])) {
            $data['id'] = $id_lead;
            NotasControllers::agregarNotas($data);
        }

        // 5️⃣ 🔔 Crear NOTIFICACIÓN
        NotifiacionesControllers::crearNotifiacion([
            'user_id'    => $user_id,
            'titulo'     => 'Nuevo Lead Asignado',
            'mensaje'    => 'Se ha creado un nuevo lead y fue asignado a usted.',
            'modulo'     => 'leads-details.php',
            'referencia' => json_encode([
                'id' => $id_lead,
                'id_cliente' => $id_cliente
            ])
        ]);

        return "ok";
    }


    public static function actualizarLeads($data, $id_cliente)
    {
        return LeadsModels::actualizarLeads($data, $id_cliente, $_SESSION['user_id'], 1);
    }

    public static function getLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin)
    {
        return LeadsModels::getLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);
    }

    public static function updateEstado($idLead, $idEstado)
    {
        $resp = LeadsModels::updateEstado($idLead, $idEstado);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Medio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function ingresarMatricula($idLead, $datos)
    {
        $resp = LeadsModels::ingresarMatricula($idLead, $datos);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Matricula agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin, $tipo)
    {
        return LeadsModels::listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin, $tipo);
    }

    public static function utm_campaign()
    {
        return LeadsModels::utm_campaign();
    }

    public static function reporte_leads_gestionado()
    {
        return LeadsModels::reporte_leads_gestionado();
    }

    public static function nuevo_leads_por_dia($fechaActual)
    {
        return LeadsModels::nuevo_leads_por_dia($fechaActual);
    }

    public static function reporteLeadsBarra()
    {
        return LeadsModels::reporteLeadsBarra();
    }

    public static function reporteLeadsPastelMotivo()
    {
        return LeadsModels::reporteLeadsPastelMotivo();
    }

    public static function reporteLeadsPastel()
    {
        return LeadsModels::reporteLeadsPastel();
    }

    public static function reporteLeadsBarraMatriculado()
    {
        return LeadsModels::reporteLeadsBarraMatriculado();
    }

    public static function listarLeadsId($id)
    {
        return LeadsModels::listarLeadsId($id);
    }

    public static function cambiarAsesor($id_lead, $user_id)
    {
        return LeadsModels::cambiarAsesor($id_lead, $user_id);
    }

    public static function actualizarColumnasLeads($leadId, $columna, $valor)
    {
        return LeadsModels::actualizarColumnasLeads($leadId, $columna, $valor);
    }

    public static function obtenerResumenHorarios($empresa, $asesor, $carreras, $horario, $estados, $fecha_inicio, $fecha_fin)
    {
        return LeadsModels::obtenerResumenHorarios($empresa, $asesor, $carreras, $horario, $estados, $fecha_inicio, $fecha_fin);
    }

    public static function consultarClienteLeads($dato)
    {
        $resp = LeadsModels::consultarClienteLeads($dato);

        if ($resp) {
            return [
                "status" => "existe",
                "message" => "El cliente ya se encuentra registrado.",
                "cliente" => $resp,
            ];
        } else {
            return [
                "status" => "no_existe",
                "message" => "Cliente no encontrado."
            ];
        }
    }

    public static function consultarClienteLeadsTele($dato)
    {
        $resp = LeadsModels::consultarClienteLeadsTele($dato);

        if ($resp) {
            return [
                "status" => "existe",
                "message" => "El cliente ya se encuentra registrado.",
                "cliente" => $resp,
            ];
        } else {
            return [
                "status" => "no_existe",
                "message" => "Cliente no encontrado."
            ];
        }
    }

    public static function actualizarLeadCompleto($data)
    {

        if (empty($_POST['cliente_id'])) {

            $nombreCompleto = trim($_POST['nombre_estudiante'] ?? '');

            $nombres = '';
            $apellidos = '';

            if ($nombreCompleto !== '') {

                // 🔹 Quitar espacios dobles
                $partes = array_values(array_filter(explode(' ', $nombreCompleto)));

                $total = count($partes);

                if ($total >= 4) {
                    // Primeras 2 → nombres | Últimas 2 → apellidos
                    $nombres   = $partes[0] . ' ' . $partes[1];
                    $apellidos = $partes[$total - 2] . ' ' . $partes[$total - 1];
                } elseif ($total === 3) {
                    // 2 nombres | 1 apellido
                    $nombres   = $partes[0] . ' ' . $partes[1];
                    $apellidos = $partes[2];
                } elseif ($total === 2) {
                    // 1 nombre | 1 apellido
                    $nombres   = $partes[0];
                    $apellidos = $partes[1];
                } elseif ($total === 1) {
                    // Solo nombre
                    $nombres   = $partes[0];
                    $apellidos = '';
                }
            }

            $asesor = LeadsModels::obtenerAsesorConMenosLeads($data);
            $user_id = $asesor['user_id'];

            $data = [
                "cod_emp"     => $_POST['cod_emp'],
                "nombresLeads"      => $nombres,
                "apellidosLeads"    => $apellidos,
                "telefonoLeads"     => $_POST['celular_estudiante_tele'],
                "correoLeads"       => $_POST['email'],
                "direLeads"        => $_POST['dire'],
                "carrera"     => $_POST["carrera"],
                "horario"     => $_POST["horario"],
                "medio"     => 0,
                "fuente"     => 0,
                "usuario"     => $_POST["user"],
                "obs"         => $_POST["observaciones"],
                "tip_tras"    => $_POST["tip_tras"],
                "bd_rst"        => 2
            ];

            $id_cliente = ClienteModels::agregarCliente($data);

            $id_lead = LeadsModels::agregarLeads($data, $id_cliente, $user_id , 3);
            $data['lead_id'] = $id_lead;
            try {
                $okObs = LeadsModels::registrarObservacion($data);
            } catch (PDOException $e) {

                // Código de error MySQL para duplicado
                if ($e->errorInfo[1] == 1062) {
                    echo json_encode([
                        "status" => "danger",
                        "message" => "Lead ya fue gestionado."
                    ]);
                    return;
                }

                // Si es otro error diferente
                echo json_encode([
                    "status" => "error",
                    "message" => "Error en la base de datos: " . $e->getMessage()
                ]);
                return;
            }

            NotifiacionesControllers::crearNotifiacion([
                'user_id'    => $_POST['user_id'],
                'titulo'     => 'Nuevo Lead Asignado RST TELECOMUNICAICONES',
                'mensaje'    => 'Se ha asignado lead desde RST TELECOMUNICAICONES',
                'modulo'     => 'leads-details.php',
                'referencia' => json_encode([
                    'id' => $_POST["id_lead"],
                    'id_cliente' => $_POST['cliente_id']
                ])
            ]);

            if ($okObs) {
                echo json_encode([
                    "status" => "ok",
                    "message" => "lead creado y cliente creado"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al actualizar la información"
                ]);
            }
        } else {
            $asesor = LeadsModels::obtenerAsesorConMenosLeads($data);
            $user_id = $asesor['user_id'];

            $data = [
                "lead_id"        => $_POST["id_lead"],
                "acudiente"      => $_POST["nombre_acudiente"],
                "tel_acudiente"  => $_POST["telefono_acudiente"],
                "carrera"        => $_POST["carrera"],
                "horario"        => $_POST["horario"],
                "usuario"        => $_POST["user"],
                "obs"            => $_POST["observaciones"],
                "tip_tras"       => $_POST["tip_tras"],
                "user_id"        => $user_id,
                "cod_emp"        => $_POST["cod_emp"],
                "bd_rst"        => 1
            ];

            $okUpdate = LeadsModels::actualizarLeadYCliente($data);
            try {
                $okObs = LeadsModels::registrarObservacion($data);
            } catch (PDOException $e) {

                // Código de error MySQL para duplicado
                if ($e->errorInfo[1] == 1062) {
                    echo json_encode([
                        "status" => "danger",
                        "message" => "Lead ya fue gestionado."
                    ]);
                    return;
                }

                // Si es otro error diferente
                echo json_encode([
                    "status" => "error",
                    "message" => "Error en la base de datos: " . $e->getMessage()
                ]);
                return;
            }

            NotifiacionesControllers::crearNotifiacion([
                'user_id'    => $user_id,
                'titulo'     => 'Nuevo Lead Asignado RST',
                'mensaje'    => 'Se ha asignado lead desde RST',
                'modulo'     => 'leads-details.php',
                'referencia' => json_encode([
                    'id' => $_POST["id_lead"],
                    'id_cliente' => $_POST['cliente_id']
                ])
            ]);

            if ($okUpdate && $okObs) {
                echo json_encode([
                    "status" => "ok",
                    "message" => "Lead y cliente actualizados correctamente"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al actualizar la información"
                ]);
            }
        }
    }

    public static function listarReporteRst($texto, $asesor)
    {
        return LeadsModels::listarReporteRst($texto, $asesor);
    }

    public static function listarReporteRstDia($mes, $anio)
    {
        return LeadsModels::listarReporteRstDia($mes, $anio);
    }

    public static function listarReporteLeadDia($mes, $anio, $asesor, $carrera, $estados)
    {
        return LeadsModels::listarReporteLeadDia($mes, $anio, $asesor, $carrera, $estados);
    }

    public static function listarLeadsFiltradosMensaje($carrera, $horario, $estado, $asesor, $numero)
    {
        return LeadsModels::listarLeadsFiltradosMensaje($carrera, $horario, $estado, $asesor, $numero);
    }

    public static function listarReporteCRMLeads($asesor, $carrera, $estados)
    {
        return LeadsModels::listarReporteCRMLeads($asesor, $carrera, $estados);
    }

    public static function reporteLeadsFuente($asesor, $carrera, $estados)
    {
        return LeadsModels::reporteLeadsFuente($asesor, $carrera, $estados);
    }
}
