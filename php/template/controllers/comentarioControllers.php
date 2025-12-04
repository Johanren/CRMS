<?php

class ComentarioControllers{
    public static function listarComentarioId($id){
        return ComentarioModels::listarComentarioId($id);
    }

    public static  function agregarComentario($dato) {
        $resp = ComentarioModels::agregarComentario($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Comentario agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}