<?php

class CarreraControllers
{
    public static function listarCarrera()
    {
        return CarreraModels::listarCarrera();
    }

    public static function agregarCarrera($dato)
    {
        $resp = CarreraModels::agregarCarrera($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Carrera agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCarreraId($id)
    {
        return CarreraModels::listarCarreraId($id);
    }

    public static function eliminarCarrera($id)
    {
        $resp = CarreraModels::eliminarCarrera($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Carrera eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
