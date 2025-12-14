<?php

class focoModels
{
    public static function agregarFoco($data)
    {
        $sql = "INSERT INTO foco (id_foc, nom_foc, fini_foc, ffin_foc, emp_foc, tot_foc) VALUES (?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["codigoFoco"]);
        $stmt->bindParam(2, $data["nombreFoco"]);
        $stmt->bindParam(3, $data["fechaInicioFoco"]);
        $stmt->bindParam(4, $data["fechaFinFoco"]);
        $stmt->bindParam(5, $_SESSION['cod_emp']);
        $stmt->bindParam(6, $data["totalCupoFoco"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function agregarFocoDetalle($data)
    {
        $sql = "INSERT INTO foco_detalle (foc_fde, prog_fde, jorn_fde, cup_fde, rein_fde, ven_fde, emp_fde) VALUES (?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["codigoFoco"]);
        $stmt->bindParam(2, $data["carrera"]);
        $stmt->bindParam(3, $data["horario"]);
        $stmt->bindParam(4, $data["cupoVentaFoco"]);
        $stmt->bindParam(5, $data['cupoReintegroFoco']);
        $stmt->bindParam(6, $data["totalCupoFoco"]);
        $stmt->bindParam(7, $_SESSION["cod_emp"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarFocoDetalle()
{
    $sql = "CALL sp_foco_existentes(?)";
    $conn = new Conexion();
    $conectar = $conn->conectar();

    $stmt = $conectar->prepare($sql);
    $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor(); // OBLIGATORIO en SP

    return $resultado;
}

}
