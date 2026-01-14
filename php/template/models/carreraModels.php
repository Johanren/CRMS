<?php

class CarreraModels
{
    public static function listarCarrera()
    {
        $emp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];
        $sql = "SELECT * FROM programa p INNER JOIN empresa e ON p.emp_pro = id_emp WHERE p.emp_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $emp);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCarrera($data)
    {
        $sql = "INSERT INTO programa (desc_pro, nlar_pro, val_pro, emp_pro) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_carr"]);
        $stmt->bindParam(2, $data["nom_carr_lar"]);
        $stmt->bindParam(3, $data["val_carr"]);
        $stmt->bindParam(4, $data["emp_carr"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function updateCarrera($data)
    {
        $sql = "UPDATE programa SET desc_pro = ?, nlar_pro = ?, val_pro = ?, emp_pro = ?, act_pro = ? WHERE cod_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_carr"]);
        $stmt->bindParam(2, $data["nom_carr_lar"]);
        $stmt->bindParam(3, $data["val_carr"]);
        $stmt->bindParam(4, $data["emp_carr"]);
        $stmt->bindParam(5, $data["act_pro"]);
        $stmt->bindParam(6, $data["carrera_id"]);
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
