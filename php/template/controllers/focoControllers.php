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

    public static function listarFoco(){
        return focoModels::listarFocoDetalle();
    }

    public static function listarLeadsFocoDetalle(){
        return focoModels::listarLeadsFocoDetalle();
    }

    public static function listarLeadsFocoResultado(){
        return focoModels::listarLeadsFocoResultado();
    }

    public static function reporteFocoActivoMatriz(){
        return focoModels::reporteFocoActivoMatriz();
    }

    public static function reporteFocoLeadsMatriz(){
        return focoModels::reporteFocoLeadsMatriz();
    }

    public static function catalogoFiltroMensaje() {
        return focoModels::catalogoFiltroMensaje();
    }
}