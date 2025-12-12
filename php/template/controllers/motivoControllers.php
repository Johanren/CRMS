<?php

class MotivoControllers
{
    public static function registrarMotivo($data)
    {
        $resp = MotivoModels::registrarMotivo($data);
        if ($resp > 0) {
            $id_mot = $resp;
            LeadsControllers::actualizarColumnasLeads($data['id'], $data['column'], $id_mot);
            return ["status" => "success", "message" => "Motivo agregado correctamente"];
        }
        return ["status" => "error", "message" => "No se pudo registrar"];
    }

    public static function listarMotivos($id)
    {
        return MotivoModels::listarMotivos($id);
    }
}
