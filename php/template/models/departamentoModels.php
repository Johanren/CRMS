<?php

class DepartamentoModels
{
    public static function listarDepartamento()
    {
        $sql = "SELECT * FROM departamento";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarDepartamento($data)
    {
        $sql = "INSERT INTO departamento (des_dep) VALUES (?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_dep"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarDepartamentoId($id) {
        $sql = "SELECT * FROM departamento WHERE cod_dep = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarDepartamento($id) {
        $sql = "DELETE FROM departamento WHERE cod_dep = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
