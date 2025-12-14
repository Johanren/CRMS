<?php

class TelefonoAdicionalControllers
{
    public static function agregarNumeroAdicional($dato, $id)
    {
        $numerosAdicionales = [];

        if (isset($dato['numerosAdicionales'])) {
            $numerosAdicionales = json_decode($dato['numerosAdicionales'], true);
        } else {
            $numerosAdicionales[] = $dato;
        }

        if (!empty($numerosAdicionales) && is_array($numerosAdicionales)) {
            foreach ($numerosAdicionales as $num) {
                $indicativo   = $num['indicativo'] ?? '';
                $numero       = $num['numero'] ?? $num['telefono'] ?? '';
                $descripcion  = $num['descripcion'] ?? '';

                if ($numero == '') continue;
                TelefonoAdicionalModels::agregarNumeroAdicional($indicativo, $numero, $descripcion, $id);
                if (!empty($dato['id'])) {
                    return [
                        "status" => true,
                        "id" => $id
                    ];
                }
            }
        }
    }

    public static function actualiarnumeroAdicional($dato, $id)
    {
        $numerosAdicionales = [];

        if (isset($dato['numerosAdicionales'])) {
            $numerosAdicionales = json_decode($dato['numerosAdicionales'], true);
        } else {
            $numerosAdicionales[] = $dato;
        }
        if (!empty($numerosAdicionales) && is_array($numerosAdicionales)) {
            foreach ($numerosAdicionales as $num) {
                $indicativo   = $num['indicativo'] ?? '';
                $numero       = $num['numero'] ?? $num['telefono'] ?? '';
                $descripcion  = $num['descripcion'] ?? '';
                $id_num           = $num['id'] ?? '';
                if ($numero == '') continue;
                TelefonoAdicionalModels::actualiarnumeroAdicional($indicativo, $numero, $descripcion, $id, $id_num);
                if (!empty($dato['id'])) {
                    return [
                        "status" => true
                    ];
                }
            }
        }
    }

    public static function eliminarnumeroAdicional($numero)
    {
        return TelefonoAdicionalModels::eliminarnumeroAdicional($numero);
    }

    public static function mdlListarTelefonosAdicionales($cliente_id)
    {
        return TelefonoAdicionalModels::mdlListarTelefonosAdicionales($cliente_id);
    }
}
