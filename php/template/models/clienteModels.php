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

    public static function consultarCliente($valor)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        // 1️⃣ Buscar datos del cliente
        $sqlCliente = "SELECT * FROM cliente WHERE identificacion = ? OR telefono_principal = ?";
        $stmt = $conectar->prepare($sqlCliente);
        $stmt->bindParam(1, $valor);
        $stmt->bindParam(2, $valor);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            return false;
        }

        // 2️⃣ Buscar teléfonos adicionales del cliente
        $sqlTelefonos = "SELECT id_numero_adicional, indicativo, telefono, descripcion 
                     FROM telefono_adicional 
                     WHERE cliente_id = ?";
        $stmtT = $conectar->prepare($sqlTelefonos);
        $stmtT->bindParam(1, $cliente['id_cliente']);
        $stmtT->execute();
        $telefonos = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        // 3️⃣ Retornar estructura combinada
        return [
            "datos" => $cliente,
            "numeros_adicionales" => $telefonos
        ];
    }
}
