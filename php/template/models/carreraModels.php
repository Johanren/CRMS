<?php

class CarreraModels
{
    public static function listarCarrera()
    {
        $sql = "SELECT * FROM programa p INNER JOIN empresa e ON p.emp_pro = id_emp WHERE p.emp_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION['cod_emp']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCarrera($data)
    {
        $sql = "INSERT INTO programa (desc_pro, val_pro, emp_pro) VALUES (?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_carr"]);
        $stmt->bindParam(2, $data["val_carr"]);
        $stmt->bindParam(3, $data["emp_carr"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function updateCarrera($data)
    {
        $sql = "UPDATE programa SET desc_pro = ?, val_pro = ?, emp_pro = ?, act_pro = ? WHERE cod_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_carr"]);
        $stmt->bindParam(2, $data["val_carr"]);
        $stmt->bindParam(3, $data["emp_carr"]);
        $stmt->bindParam(4, $data["act_pro"]);
        $stmt->bindParam(5, $data["carrera_id"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCarreraId($id)
    {
        $sql = "SELECT * FROM programa WHERE cod_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCarrera($id)
    {
        $sql = "DELETE FROM programa WHERE cod_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return "ok";
    }
}
