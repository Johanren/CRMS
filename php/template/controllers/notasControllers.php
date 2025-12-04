<?php

class NotasControllers
{

    public static function agregarNotas($dato)
    {
        $resp = NotasModels::agregarNotas($dato);

        if ($resp > 0) {

            // Guardar archivos si existen
            if (!empty($_FILES["desc_arch"]["name"][0])) {
                ArchivoControllers::agregarArchivo($_FILES["desc_arch"], $resp, "nota");
            }

            return ["status" => "success", "message" => "Notas agregadas correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarNotasId($id)
    {
        $notas = NotasModels::listarNotasId($id);

        foreach ($notas as &$n) {
            $n["archivos"] = ArchivoControllers::listarArchivoId($n["cod_not"]);
            $n["comentarios"] = ComentarioControllers::listarComentarioId($n["cod_not"]);
        }

        return $notas;
    }
}
