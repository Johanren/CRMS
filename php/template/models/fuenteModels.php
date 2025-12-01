<?php

class FuenteModels
{
    public static function listarFuente()
    {
        $sql = "SELECT * FROM fuente1 f INNER JOIN medio1 m ON f.med_fue = m.cod_med";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarFuente($data)
    {
        $sql = "INSERT INTO fuente1 (desc_fue, med_fue) VALUES (?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["des_fnt"]);
        $stmt->bindParam(2, $data["med_fue"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarFuenteId($id)
    {
        $sql = "SELECT * FROM fuente1 WHERE cod_fue = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarFuente($id)
    {
        $sql = "DELETE FROM fuente1 WHERE cod_fue = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarFuentePorMedio($id)
    {
        $sql = "SELECT * FROM fuente1 WHERE med_fue = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
