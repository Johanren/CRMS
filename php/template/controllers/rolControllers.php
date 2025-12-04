<?php

class RolControllers{
    public static function listarRol(){
        return RolModels::listarRol();
    }

    public static function agregarRol($dato){
        $resp = RolModels::agregarRol($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Rol agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarRolId($id){
        return RolModels::listarRolId($id);
    }

    public static function eliminarRol($id) {
        $resp = RolModels::eliminarRol($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Rol eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}