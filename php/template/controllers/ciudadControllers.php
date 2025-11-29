<?php

class CiudadControllers
{
    public static function listarCiudad()
    {
        return CiudadModels::listarCiudad();
    }

    public static function agregarCiudad($dato)
    {
        $resp = CiudadModels::agregarCiudad($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Ciudad agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCiudadId($id)
    {
        return CiudadModels::listarCiudadId($id);
    }

    public static function eliminarCiudad($id)
    {
        $resp = CiudadModels::eliminarCiudad($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Ciudad eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCiudadPorDepartamento($id)
    {
        return CiudadModels::listarCiudadPorDepartamento($id);
    }
}
