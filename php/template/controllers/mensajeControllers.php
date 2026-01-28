<?php

class MensajeControllers{
    public static function agregarMensaje($data){
        return MensajeModels::agregarMensaje($data);
    }

    public static function listarMensaje(){
        return MensajeModels::listarMensaje();  
    }

    public static function obtenerMensaje($id){
        return MensajeModels::obtenerMensaje($id);
    }

    public static function actualizarMesaje($data){
        return MensajeModels::actualizarMesaje($data);
    }

    public static function eliminar_mensaje($id){
        return MensajeModels::eliminar_mensaje($id);
    }

    public static function listarMensajesParametrizados(){
        return MensajeModels::listarMensajesParametrizados();
    }
}