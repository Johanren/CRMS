<?php

class NotifiacionesControllers
{
    public static function crearNotifiacion($data)
    {
        return NotificacionesModels::crearNotificacion($data);
    }

    public static function listarTodas()
    {
        return NotificacionesModels::listarTodas();
    }

    public static function listarPorUsuario($user_id, $limit)
    {
        return NotificacionesModels::listarPorUsuario($user_id, $limit);
    }

    public static function marcarLeida($id)
    {
        return NotificacionesModels::marcarLeida($id);
    }

    public static function contarNoLeidas($user_id)
    {
        return NotificacionesModels::contarNoLeidas($user_id);
    }
}
