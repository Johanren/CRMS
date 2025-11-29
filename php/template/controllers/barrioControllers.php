<?php

class BarrioControllers
{
    public static function listarBarrio()
    {
        return BarrioModels::listarBarrio();
    }

    public static function agregarBarrio($dato)
    {
        $resp = BarrioModels::agregarBarrio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Barrio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarBarrioId($id)
    {
        return BarrioModels::listarBarrioId($id);
    }

    public static function eliminarBarrio($id)
    {
        $resp = BarrioModels::eliminarBarrio($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Barrio eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
    public static function listarBarrioPorCiudad($id)
    {
        return BarrioModels::listarBarrioPorCiudad($id);
    }
}
