<?php

class AcortadorUrlsModels
{

    public static function guardarUrlsAcortador($data)
    {
        /*==============================
        GUARDAR MENSAJE EN CRM
        ==============================*/

        $sql = "INSERT INTO urls 
        (mensaje, carrera, jornada, final_url, url_original, short_url) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data['mensaje']);
        $stmt->bindParam(2, $data['carrera']);
        $stmt->bindParam(3, $data['horario']);
        $stmt->bindParam(4, $data['slug']);
        $stmt->bindParam(5, $data['original_url']);
        $stmt->bindParam(6, $data['short_url']);

        if ($stmt->execute()) {

            /*==============================
            GUARDAR SOLO URL EN ACORTADOR
            ==============================*/

            $sql2 = "INSERT INTO urls (original_url, short_url) VALUES (?, ?)";

            $conn2 = new Conexion();
            $conectar2 = $conn2->conectarUrls();

            $stmt2 = $conectar2->prepare($sql2);

            $stmt2->bindParam(1, $data['original_url']);
            $stmt2->bindParam(2, $data['short_url']);

            if ($stmt2->execute()) {
                return ["ok" => true];
            }
        }

        return ["ok" => false];
    }
}
