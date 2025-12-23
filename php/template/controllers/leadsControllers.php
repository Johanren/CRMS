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

    public static function listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin)
    {
        return LeadsModels::listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);
    }

    public static function utm_campaign()
    {
        return LeadsModels::utm_campaign();
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
}
