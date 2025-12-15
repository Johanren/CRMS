<?php

class focoModels
{
    public static function agregarFoco($data)
    {
        try {

            $conn = new Conexion();
            $db = $conn->conectar();

            $db->beginTransaction();

            // Verificar si el FOCO ya existe
            $sqlCheck = "SELECT id_foc 
                     FROM foco 
                     WHERE id_foc = ? AND emp_foc = ?";
            $stmtCheck = $db->prepare($sqlCheck);
            $stmtCheck->execute([
                $data["codigoFoco"],
                $_SESSION["cod_emp"]
            ]);

            $foco = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Si NO existe → crear FOCO
            if (!$foco) {

                $sqlInsertFoco = "INSERT INTO foco 
                (id_foc, nom_foc, fini_foc, ffin_foc, emp_foc, tot_foc)
                VALUES (?,?,?,?,?,?)";

                $stmtFoco = $db->prepare($sqlInsertFoco);
                $stmtFoco->execute([
                    $data["codigoFoco"],
                    $data["nombreFoco"],
                    $data["fechaInicioFoco"],
                    $data["fechaFinFoco"],
                    $_SESSION["cod_emp"],
                    $data["totalCupoFoco"]
                ]);
            }

            // Registrar SIEMPRE el detalle
            $sqlDetalle = "INSERT INTO foco_detalle 
            (foc_fde, prog_fde, jorn_fde, cup_fde, rein_fde, ven_fde, emp_fde)
            VALUES (?,?,?,?,?,?,?)";

            $stmtDetalle = $db->prepare($sqlDetalle);
            $stmtDetalle->execute([
                $data["codigoFoco"],
                $data["carrera"],
                $data["horario"],
                $data["cupoVentaFoco"],
                $data["cupoReintegroFoco"],
                $data["totalCupoFoco"],
                $_SESSION["cod_emp"]
            ]);

            $db->commit();

            return [
                "status" => "success",
                "foco_id" => $data["codigoFoco"],
                "message" => "Foco y detalle guardados correctamente"
            ];
        } catch (Exception $e) {

            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return [
                "status" => "error",
                "message" => "Error al guardar foco: " . $e->getMessage()
            ];
        }
    }


    public static function listarFocoDetalle()
    {
        $sql = "SELECT 
                h.descripcion AS jornada,
                p.desc_pro AS programa,
                SUM(fd.cup_fde) AS cupos,
                SUM(fd.ven_fde) AS ventas,
                SUM(fd.rein_fde) AS reintegros,
                f.nom_foc AS foco,
                f.fini_foc AS fecha_inicio,
                f.ffin_foc AS fecha_fin
            FROM foco_detalle fd
            INNER JOIN foco f 
                ON f.id_foc = fd.foc_fde
                AND f.emp_foc = fd.emp_fde
            INNER JOIN programa p ON p.cod_pro = fd.prog_fde
            INNER JOIN horario h ON h.id_horario = fd.jorn_fde
            WHERE 
                fd.emp_fde = ?
                AND CURDATE() BETWEEN f.fini_foc AND f.ffin_foc
            GROUP BY 
                h.descripcion, p.desc_pro, f.nom_foc, f.fini_foc, f.ffin_foc
            ORDER BY h.descripcion, p.desc_pro
            ";
        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    public static function actulizarFocoDetalle($data)
    {
        $sql = "UPDATE foco_detalle fd INNER JOIN programa p ON p.cod_pro = fd.prog_fde INNER JOIN horario h ON h.id_horario = fd.jorn_fde SET fd.cup_fde = ?, fd.rein_fde = ?, fd.ven_fde = ? WHERE h.descripcion = ? AND p.desc_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data['ventas']);
        $stmt->bindParam(2, $data['reintegros']);
        $stmt->bindParam(3, $data['cupos']);
        $stmt->bindParam(4, $data['jornada']);
        $stmt->bindParam(5, $data['programa']);
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Foco detalle actualizado'];
        }

        return "error";
    }

    public static function eliminarFocoDetalle($data)
    {
        $sql = "DELETE fd FROM foco_detalle fd INNER JOIN programa p ON p.cod_pro = fd.prog_fde INNER JOIN horario h ON h.id_horario = fd.jorn_fde WHERE h.descripcion = ? AND p.desc_pro = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $data['jornada']);
        $stmt->bindParam(2, $data['programa']);
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Foco detalle actualizado'];
        }

        return "error";
    }

    public static function reporteFocoActivoMatriz()
    {
        $sql = "SELECT 
            h.descripcion AS jornada,
            p.desc_pro AS programa,
            SUM(fd.cup_fde) AS cupos,
            SUM(fd.ven_fde) AS ventas,
            SUM(fd.rein_fde) AS reintegros
        FROM foco_detalle fd
        INNER JOIN foco f ON f.id_foc = fd.foc_fde
        INNER JOIN programa p ON p.cod_pro = fd.prog_fde
        INNER JOIN horario h ON h.id_horario = fd.jorn_fde
        WHERE 
            fd.emp_fde = ?
            AND CURDATE() BETWEEN f.fini_foc AND f.ffin_foc
        GROUP BY h.descripcion, p.desc_pro
            ";
        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->execute();

        $matriz = [];
        $programas = [];
        $jornadas = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

            $jornada  = $row["jornada"];
            $programa = $row["programa"];

            $jornadas[$jornada] = true;
            $programas[$programa] = true;

            $matriz[$jornada][$programa] = [
                "cupos"       => (int)$row["cupos"],
                "ventas"      => (int)$row["ventas"],
                "reintegros"  => (int)$row["reintegros"]
            ];
        }

        return [
            "matriz"    => $matriz,
            "jornadas"  => array_keys($jornadas),
            "programas" => array_keys($programas)
        ];
    }
}
