<?php

class focoControllers
{
    public static function agregarFoco($data)
    {
        $resp = focoModels::agregarFoco($data);
        if ($resp == "ok") {
            $respDetalle = focoModels::agregarFocoDetalle($data);
            if ($resp == "ok") {
                return ["status" => "success", "message" => "Foco agregado correctamente"];
            } else {
                return ["status" => "error", "message" => "No se pudo registrar"];
            }
        }
    }

    public static function listarFoco(){
        return focoModels::listarFocoDetalle();
    }
}
