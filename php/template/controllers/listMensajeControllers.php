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

}