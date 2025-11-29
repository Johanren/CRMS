<?php

class FuenteControllers
{
    public static function listarFuente()
    {
        return FuenteModels::listarFuente();
    }

    public static function agregarFuente($dato)
    {
        $resp = FuenteModels::agregarFuente($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Fuente agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarFuenteId($id)
    {
        return FuenteModels::listarFuenteId($id);
    }

    public static function eliminarFuente($id)
    {
        $resp = FuenteModels::eliminarFuente($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Fuente eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
