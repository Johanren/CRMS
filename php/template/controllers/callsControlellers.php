<?php

class CallsControlellers{
    public static function agregarCalls($data){
        $resp = CallsModels::agregarCalls($data);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Llamada agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCallsId($id){
        return CallsModels::listarCallsId($id);
    }

    public static function actualizarEstadoCall($data){
        $resp = CallsModels::actualizarEstadoCall($data);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Estado actualizado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}