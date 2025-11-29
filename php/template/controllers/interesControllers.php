<?php

class InteresControllers
{
    public static function listarInteres()
    {
        return InteresModels::listarInteres();
    }

    public static function agregarInteres($dato)
    {
        $resp = InteresModels::agregarInteres($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Interes agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarInteresId($id)
    {
        return InteresModels::listarInteresId($id);
    }

    public static function eliminarInteres($id)
    {
        $resp = InteresModels::eliminarInteres($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Interes eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
