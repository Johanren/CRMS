<?php

class focoControllers
{
    public static function agregarFoco($data)
    {
        return focoModels::agregarFoco($data);
    }

    public static function actulizarFocoDetalle($data)
    {
        return focoModels::actulizarFocoDetalle($data);
    }

    public static function eliminarFocoDetalle($data)
    {
        return focoModels::eliminarFocoDetalle($data);
    }

    public static function listarFoco()
    {
        return focoModels::listarFocoDetalle();
    }

    public static function listarLeadsFocoDetalle()
    {
        return focoModels::listarLeadsFocoDetalle();
    }

    public static function listarLeadsFocoResultado($asesor, $carrera, $estados)
    {
        return focoModels::listarLeadsFocoResultado($asesor, $carrera, $estados);
    }

    public static function reporteFocoActivoMatriz()
    {
        return focoModels::reporteFocoActivoMatriz();
    }

    public static function reporteFocoLeadsMatriz()
    {
        return focoModels::reporteFocoLeadsMatriz();
    }

    public static function catalogoFiltroMensaje()
    {
        return focoModels::catalogoFiltroMensaje();
    }

    public static function consultarFocoFecha()
    {
        return focoModels::consultarFocoFecha();
    }

    public static function ctrCerrarFoco()
    {

        if (!isset($_SESSION['foco']) || !isset($_SESSION['cod_emp'])) {
            return [
                "status" => "error",
                "message" => "Sesión inválida"
            ];
        }

        $focoActual = $_SESSION['foco'];
        $empresa    = $_SESSION['cod_emp'];

        $respuesta = focoModels::mdlCerrarFoco($focoActual, $empresa);

        if ($respuesta["status"] === "success") {
            $_SESSION['foco'] = $respuesta["nuevo_foco"];
        }

        if ($respuesta["status"] === "success") {
            $_SESSION['foco'] = $respuesta["nuevo_foco"];
        }

        return $respuesta;
    }
}
