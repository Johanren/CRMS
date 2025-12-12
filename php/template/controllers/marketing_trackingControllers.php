<?php

class Marketing_trackingControllers{
    public static function agregarClick($dato) {
        Marketing_trackingModels::agregarClick($dato);
        
    }

    public static function consultarClickExistete($ip){
        $resp = Marketing_trackingModels::consultarClickExistete($ip);
        if (isset($ip['identificacionLeads'])) {
            return $resp;
        }
        if ($resp) {
            return ["status" => "existe"];
        }
        return ["status" => "no_existe"];
    }

    public static function updateClickMarketing($id_marketing, $cliente_id){
        Marketing_trackingModels::updateClickMarketing($id_marketing, $cliente_id);
    }
}