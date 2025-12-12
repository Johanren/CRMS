<?php

class TelefonoAdicionalControllers
{
    public static function agregarNumeroAdicional($dato, $id)
    {
        $numerosAdicionales = json_decode($dato['numerosAdicionales'], true);
        if (!empty($numerosAdicionales) && is_array($numerosAdicionales)) {
            foreach ($numerosAdicionales as $num) {
                $indicativo   = $num['indicativo'] ?? '';
                $numero       = $num['numero'] ?? '';
                $descripcion  = $num['descripcion'] ?? '';

                if ($numero == '') continue; 
                TelefonoAdicionalModels::agregarNumeroAdicional($indicativo, $numero, $descripcion, $id);
            }
        }
    }
}
