<?php

class AcortadorUrlsControllers{
    public static function guardarUrlsAcortador($data){
        return AcortadorUrlsModels::guardarUrlsAcortador($data);
    }
    public static function cargarUrlsAcortador(){
        return AcortadorUrlsModels::cargarUrlsAcortador();
    }
}