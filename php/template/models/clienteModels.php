<?php

class ClienteModels
{
    public static function listarCliente()
    {
        $sql = "SELECT * FROM cliente";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCliente($data)
    {
        $sql = "INSERT INTO cliente (identificacion, nombres, apellidos, telefono_principal, email, direccion, barrio_id, ciudad_id) VALUES (?,?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["identificacionLeads"]);
        $stmt->bindParam(2, $data["nombresLeads"]);
        $stmt->bindParam(3, $data["apellidosLeads"]);
        $stmt->bindParam(4, $data["telefonoLeads"]);
        $stmt->bindParam(5, $data["correoLeads"]);
        $stmt->bindParam(6, $data["direLeads"]);
        $stmt->bindParam(7, $data["barrio"]);
        $stmt->bindParam(8, $data["ciudad"]);
        if ($stmt->execute()) {
            $ultimoId = $conectar->lastInsertId();
            return $ultimoId;
        }

        return "error";
    }

    public static function actualizarCliente($data)
    {
        $sql = "UPDATE cliente SET identificacion = ?, nombres = ?, apellidos = ?, telefono_principal = ?, email = ?, direccion = ?, barrio_id = ?, ciudad_id = ? WHERE id_cliente = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["identificacionLeads"]);
        $stmt->bindParam(2, $data["nombresLeads"]);
        $stmt->bindParam(3, $data["apellidosLeads"]);
        $stmt->bindParam(4, $data["telefonoLeads"]);
        $stmt->bindParam(5, $data["correoLeads"]);
        $stmt->bindParam(6, $data["direLeads"]);
        $stmt->bindParam(7, $data["barrio"]);
        $stmt->bindParam(8, $data["ciudad"]);
        $stmt->bindParam(9, $data["cliente_id"]);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarClienteId($id)
    {
        $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCliente($id)
    {
        $sql = "DELETE FROM cliente WHERE id_cliente = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
