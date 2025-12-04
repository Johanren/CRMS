<?php

class ArchivoControllers
{
    public static function agregarArchivo($files, $id, $tipo)
    {
        $ruta = "uploads/notas/";

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        // Recorrer los archivos enviados
        for ($i = 0; $i < count($files["name"]); $i++) {

            $nombreOriginal = $files["name"][$i];
            $tmp = $files["tmp_name"][$i];

            // Nombre único
            $nuevoNombre = uniqid() . "_" . $nombreOriginal;

            if (move_uploaded_file($tmp, $ruta . $nuevoNombre)) {

                ArchivoModels::agregarArchivo($nuevoNombre, $id, $tipo);
            }
        }
    }

    public static function listarArchivoId($id){
        return ArchivoModels::listarArchivoId($id);
    }
}
