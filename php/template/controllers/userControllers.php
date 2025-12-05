<?php

class UserControllers{
    public static function listarUser(){
        return UserModels::listarUser();
    }

    public static function agregarUser($data){
        $resp = UserModels::agregarUser($data);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "User agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function actualizarUser($data){
        $resp = UserModels::actualizarUser($data);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "User actualizado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarUserId($id){
        return UserModels::listarUserId($id);
    }

    public static function eliminarUser($id){
        $resp = UserModels::eliminarUser($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "User eliminado correctamente"];
        }elseif ($resp == "usuario_existente") {
            return ["status" => "success", "message" => "User ya existente"];
        } 
        else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}