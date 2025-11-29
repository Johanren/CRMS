<?php

class DepartamentoControllers
{
    public static function listarDepartamento()
    {
        return DepartamentoModels::listarDepartamento();
    }

    public static function agregarDepartamento($dato)
    {
        $resp = DepartamentoModels::agregarDepartamento($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Departamento agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarDepartamentoId($id)
    {
        return DepartamentoModels::listarDepartamentoId($id);
    }

    public static function eliminarDepartamento($id)
    {
        $resp = DepartamentoModels::eliminarDepartamento($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Departamento eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
