<?php

class LeadsControllers
{
    public static function agregarLeads($data, $id_cliente)
    {

        // Si existe user_id en sesión → usarlo
        $user_id = $_SESSION['user_id'] ?? null;

        // Si NO existe → buscar asesor con menos leads
        if (!$user_id) {
            $asesor = LeadsModels::obtenerAsesorConMenosLeads($data);
            $user_id = $asesor['user_id'];
        }
        if (!empty($data['tit_not']) || !empty($data['desc_not']) || !empty($data['desc_arch'])) {
            $id_leads = LeadsModels::agregarLeads($data, $id_cliente, $user_id, 2);
            $data['id'] = $id_leads;
            NotasControllers::agregarNotas($data);
            return "ok";
        }
        // Estado por defecto 1
        return LeadsModels::agregarLeads($data, $id_cliente, $user_id, 1);
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

    public static function listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin)
    {
        return LeadsModels::listarLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);
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
}
