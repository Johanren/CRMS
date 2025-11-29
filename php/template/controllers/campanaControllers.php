<?php

class CampanaControllers{
    public static function agregarCampana($dato){
        $resp = CampanaModels::agregarCampana($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCampana() {
        return CampanaModels::listarCampana();
    }

    public static function listarCampanaId($id) {
        return CampanaModels::listarCampanaId($id);
    }

    public static function eliminarCampana($id) {
        $resp = CampanaModels::eliminarCampana($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }
}