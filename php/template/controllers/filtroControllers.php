<?php

class FiltroControllers
{
    public static function agregarFiltro($id_user, $nombre, $filtro) {
        return FiltroModels::agregarFiltro($id_user, $nombre, $filtro);
    }

    public static function cargarFiltro($id_user, $nombre) {
        return FiltroModels::cargarFiltro($id_user, $nombre);
    }
}
