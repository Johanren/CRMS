<?php

class ProximaActividadControllers
{
    public static function agregarProximaActividad($data) {
        $resp = ProximaActividadModels::agregarProximaActividad($data);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Proxima Actividad agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarProximaActividadId($id){
        return ProximaActividadModels::listarProximaActividadId($id);
    }
}
