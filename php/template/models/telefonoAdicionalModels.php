<?php

class TelefonoAdicionalModels
{
    public static function agregarNumeroAdicional($indicativo, $numero, $descripcion, $id)
    {
        $sql = "INSERT INTO telefono_adicional (cliente_id, indicativo, telefono, descripcion) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $indicativo);
        $stmt->bindParam(3, $numero);
        $stmt->bindParam(4, $descripcion);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }
}
