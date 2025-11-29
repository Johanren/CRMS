<?php

class MedioControllers
{
    public static function listarMedio()
    {
        return MedioModels::listarMedio();
    }

    public static function agregarMedio($dato)
    {
        $resp = MedioModels::agregarMedio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Medio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarMedioId($id)
    {
        return MedioModels::listarMedioId($id);
    }

    public static function eliminarMedio($id)
    {
        $resp = MedioModels::eliminarMedio($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Medio eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
