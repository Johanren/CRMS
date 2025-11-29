<?php

class AccionControllers
{
    public static function listarAccion()
    {
        return AccionModels::listarAccion();
    }

    public static function agregarAccion($dato)
    {
        $resp = AccionModels::agregarAccion($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Accion agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarAccionId($id)
    {
        return AccionModels::listarAccionId($id);
    }

    public static function eliminarAccion($id)
    {
        $resp = AccionModels::eliminarAccion($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Accion eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
