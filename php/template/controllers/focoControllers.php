<?php

class focoControllers
{
    public static function agregarFoco($data)
    {
        return focoModels::agregarFoco($data);
    }

    public static function listarFoco(){
        return focoModels::listarFocoDetalle();
    }

    public static function reporteFocoActivoMatriz(){
        return focoModels::reporteFocoActivoMatriz();
    }
}
