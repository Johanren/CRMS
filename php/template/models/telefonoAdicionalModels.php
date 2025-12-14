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

    public static function actualiarnumeroAdicional($indicativo, $numero, $descripcion, $id, $id_num)
    {
        $sql = "UPDATE telefono_adicional SET cliente_id = ?, indicativo = ?, telefono = ?, descripcion = ? WHERE id_numero_adicional = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $indicativo);
        $stmt->bindParam(3, $numero);
        $stmt->bindParam(4, $descripcion);
        $stmt->bindParam(5, $id_num);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function eliminarnumeroAdicional($numero)
    {
        $sql = "DELETE FROM telefono_adicional WHERE telefono = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $numero);
        $stmt->execute();
        return [
            "status" => true
        ];
    }

    public static function mdlListarTelefonosAdicionales($cliente_id)
    {
        $sql = "SELECT id_numero_adicional, indicativo, telefono, descripcion
        FROM telefono_adicional
        WHERE cliente_id = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
