<?php

class ListMensajeControllers{
    public static function guardarMensajesRST() {

        if (!isset($_POST['mensajes'])) {
            echo json_encode(['ok' => false, 'error' => 'No data']);
            return;
        }

        $mensajes = json_decode($_POST['mensajes'], true);

        $fechaProgramada = $_POST['programar'] ?? null;    

        return ListMensajeModels::insertarMensajes($mensajes, $fechaProgramada);
    }

    public static function listarMensajesRST($id){
        return ListMensajeModels::listarMensajesRST($id);
    }

    public static function reporte1Mensajes()
    {
        return ListMensajeModels::reporte1Mensajes();
    }

    public static function reporte2Estados()
    {
        return ListMensajeModels::reporte2Estados();
    }

    public static function reporte3Asesores()
    {
        return ListMensajeModels::reporte3Asesores();
    }

}