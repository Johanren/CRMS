<?php

class AcortadorUrlsControllers{
    public static function guardarUrlsAcortador($data){
        return AcortadorUrlsModels::guardarUrlsAcortador($data);
    }
}