<?php

class ArchivoModels
{
    public static function agregarArchivo($nombreArchivo, $id, $tipo)
    {
        $sql = "INSERT INTO archivo (desc_arch, tipo_adjun, cod_adju) VALUES (?, ?, ?)";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $nombreArchivo);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $id);

        return $stmt->execute() ? "ok" : "error";
    }

    public static function listarArchivoId($id)
    {
        $sql = "SELECT * FROM archivo 
            WHERE cod_adju = ? AND tipo_adjun = 'nota'";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
