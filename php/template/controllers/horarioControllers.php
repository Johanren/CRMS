<?php

class HorarioControllers
{
    public static function listarHorario()
    {
        return HorarioModels::listarHorario();
    }

    public static function agregarHorario($dato)
    {
        $resp = HorarioModels::agregarHorario($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Horario agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarHorarioId($id)
    {
        return HorarioModels::listarHorarioId($id);
    }

    public static function eliminarHorario($id)
    {
        $resp = HorarioModels::eliminarHorario($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Horario eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
