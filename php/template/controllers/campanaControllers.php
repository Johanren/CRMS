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

    public static function agregarCampanas($dato, $foto){
        $resp = CampanaModels::agregarCampanas($dato, $foto);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function agregarCampanasxmedio($dato){
        $resp = CampanaModels::agregarCampanasxmedio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function actuaizarCampanas($dato, $foto){
        $resp = CampanaModels::actuaizarCampanas($dato, $foto);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña actualizada correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function actuaizarCampanasxmedio($dato){
        $resp = CampanaModels::actuaizarCampanasxmedio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio actualizada correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCampana() {
        return CampanaModels::listarCampana();
    }

    public static function listarCampanas() {
        return CampanaModels::listarCampanas();
    }

    public static function listarCampanasxmedio() {
        return CampanaModels::listarCampanasxmedio();
    }

    public static function listarCampanaId($id) {
        return CampanaModels::listarCampanaId($id);
    }

    public static function listarCampanasId($id) {
        return CampanaModels::listarCampanasId($id);
    }

    public static function listarCampanasxmedioId($id) {
        return CampanaModels::listarCampanasxmedioId($id);
    }

    public static function eliminarCampana($id) {
        $resp = CampanaModels::eliminarCampana($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }

    public static function eliminarCampanas($id) {
        $resp = CampanaModels::eliminarCampana($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }

    public static function eliminarCampanasxmedio($id) {
        $resp = CampanaModels::eliminarCampanasxmedio($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }
}