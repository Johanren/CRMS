<?php

class AuditoriaModels
{

    public static function listarAuditoria()
    {
        $sql = "SELECT * FROM tipo_audiencia";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
