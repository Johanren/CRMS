<?php

class LeadsControllers
{
    public static function agregarLeads($data, $id_cliente)
    {

        // Si existe user_id en sesión → usarlo
        $user_id = $_SESSION['user_id'] ?? null;

        // Si NO existe → buscar asesor con menos leads
        if (!$user_id) {
            $asesor = LeadsModels::obtenerAsesorConMenosLeads();
            $user_id = $asesor['user_id'];
        }

        // Estado por defecto 1
        return LeadsModels::agregarLeads($data, $id_cliente, $user_id, 1);
    }

    public static function actualizarLeads($data, $id_cliente)
    {
        return LeadsModels::actualizarLeads($data, $id_cliente, $_SESSION['user_id'], 1);
    }

    public static function getLeads($texto, $carreras, $estados)
    {
        return LeadsModels::getLeads($texto, $carreras, $estados);
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

    public static function listarLeads($texto, $carreras, $estados)
    {
        return LeadsModels::listarLeads($texto, $carreras, $estados);
    }

    public static function listarLeadsId($id)
    {
        return LeadsModels::listarLeadsId($id);
    }

    public static function cambiarAsesor($id_lead, $user_id) {
        return LeadsModels::cambiarAsesor($id_lead, $user_id);
    }
}
