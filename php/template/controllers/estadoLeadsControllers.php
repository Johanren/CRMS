<?php

class EstadoLeadsControllersControllers
{
    public static function listarEstadoLeads()
    {
        return EstadoLeadsModels::listarEstadoLeads();
    }

    public static function agregarEstadoLeads($dato)
    {
        $resp = EstadoLeadsModels::agregarEstadoLeads($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "EstadoLeads agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarEstadoLeadsId($id)
    {
        return EstadoLeadsModels::listarEstadoLeadsId($id);
    }

    public static function eliminarEstadoLeads($id)
    {
        $resp = EstadoLeadsModels::eliminarEstadoLeads($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "EstadoLeads eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
