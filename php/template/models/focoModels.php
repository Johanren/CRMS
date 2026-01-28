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
                AND f.id_foc = ?
            GROUP BY 
                h.descripcion, p.desc_pro, f.nom_foc, f.fini_foc, f.ffin_foc
            ORDER BY h.descripcion, p.desc_pro
            ";
        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION["foco"], PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    public static function listarLeadsFocoDetalle()
    {
        $id_user = $_SESSION["user_id"];
        $sql = "SELECT
                h.descripcion AS jornada,
                h.id_horario AS id_jornada,
                p.desc_pro AS programa,

                /* foco_detalle */
                fd.cup_fde AS cupos,
                fd.ven_fde AS ventas,
                fd.rein_fde AS reintegros,

                /* foco */
                f.nom_foc AS foco,
                f.fini_foc AS fecha_inicio,
                f.ffin_foc AS fecha_fin,

                /* Leads con horario EXACTO */
                COUNT(DISTINCT lh.id_lead) AS con_horario,

                /* Leads de la carrera pero con horario distinto o NULL */
                COUNT(DISTINCT ls.id_lead) AS solo_carrera

            FROM foco_detalle fd
            INNER JOIN foco f 
                ON f.id_foc = fd.foc_fde
            AND f.emp_foc = fd.emp_fde

            INNER JOIN programa p 
                ON p.cod_pro = fd.prog_fde

            INNER JOIN horario h 
                ON h.id_horario = fd.jorn_fde

            /* Leads con horario correcto */
            LEFT JOIN leads lh
                ON lh.carrera_id = fd.prog_fde
            AND lh.horario_id = fd.jorn_fde
            AND lh.cod_emp = f.emp_foc
            AND lh.estado_leads_id NOT IN (6,7,8)
            ";
        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= "
            AND lh.user_id = '$id_user'";
        }
        $sql .= "

            /* Leads solo carrera (horario distinto o NULL) */
            LEFT JOIN leads ls
                ON ls.carrera_id = fd.prog_fde
            AND ls.cod_emp = f.emp_foc
            AND ls.estado_leads_id NOT IN (6,7,8)
            ";
        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= "
            AND ls.user_id = '$id_user'
            ";
        }
        $sql .= "
            AND (
                    ls.horario_id <> fd.jorn_fde
                    OR ls.horario_id IS NULL
            )

            WHERE 
                f.emp_foc = ?
                AND f.id_foc = ?

            GROUP BY
                h.descripcion,
                p.desc_pro,
                fd.cup_fde,
                fd.ven_fde,
                fd.rein_fde,
                f.nom_foc,
                f.fini_foc,
                f.ffin_foc

            ORDER BY
                h.descripcion,
                p.desc_pro;";

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION["foco"], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarLeadsFocoResultado()
    {
        $sql = "SELECT
                h.descripcion AS jornada,
                p.desc_pro AS programa,
                p.val_pro AS valor_programa,

                /* foco_detalle */
                fd.cup_fde AS cupos,
                fd.ven_fde AS ventas,
                fd.rein_fde AS reintegros,

                /* foco */
                f.nom_foc AS foco,
                f.fini_foc AS fecha_inicio,
                f.ffin_foc AS fecha_fin,

                /* Leads con horario EXACTO */
                COUNT(DISTINCT lh.id_lead) AS con_horario,

                /* Leads de la carrera pero con horario distinto o NULL */
                COUNT(DISTINCT ls.id_lead) AS solo_carrera,

                /* Ventas (solo estado 6) */
                COUNT(DISTINCT lv.id_lead) AS ventas_estado_6

            FROM foco_detalle fd
            INNER JOIN foco f 
                ON f.id_foc = fd.foc_fde
            AND f.emp_foc = fd.emp_fde

            INNER JOIN programa p 
                ON p.cod_pro = fd.prog_fde

            INNER JOIN horario h 
                ON h.id_horario = fd.jorn_fde

            /* Leads con horario correcto */
            LEFT JOIN leads lh
                ON lh.carrera_id = fd.prog_fde
            AND lh.horario_id = fd.jorn_fde
            AND lh.cod_emp = f.emp_foc
            AND lh.estado_leads_id NOT IN (6,7,8)
            ";
        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= "
            AND lh.user_id = :user_id";
        }
        $sql .= "
            LEFT JOIN leads lv
            ON lv.carrera_id = fd.prog_fde
            AND lv.horario_id = fd.jorn_fde
            AND lv.cod_emp = f.emp_foc
            AND lv.estado_leads_id = 6
            AND lv.Nfactura IS NOT NULL
            AND lv.valorF IS NOT NULL
            AND lv.metodoF IS NOT NULL
            ";
        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= "
            AND lv.user_id = ':user_id";
        }
        $sql .= "
            /* Leads solo carrera (horario distinto o NULL) */
            LEFT JOIN leads ls
                ON ls.carrera_id = fd.prog_fde
            AND ls.cod_emp = f.emp_foc
            AND ls.estado_leads_id NOT IN (6,7,8)
            ";
        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= "
            AND ls.user_id = :user_id
            ";
        }
        $sql .= "
            AND (
                    ls.horario_id <> fd.jorn_fde
                    OR ls.horario_id IS NULL
            )

            WHERE 
                f.emp_foc = :cod_emp
                AND f.id_foc = :foco

            GROUP BY
                h.descripcion,
                p.desc_pro,
                fd.cup_fde,
                fd.ven_fde,
                fd.rein_fde,
                f.nom_foc,
                f.fini_foc,
                f.ffin_foc

            ORDER BY
                h.descripcion,
                p.desc_pro;";

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':cod_emp', $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->bindParam(':foco', $_SESSION["foco"], PDO::PARAM_INT);
        if ($_SESSION['rol'] !== 'Admin') {
            $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            AND f.id_foc = ?
        GROUP BY h.descripcion, p.desc_pro
            ";
        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION["foco"], PDO::PARAM_INT);
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

    public static function reporteFocoLeadsMatriz()
    {
        $id_user = $_SESSION["user_id"];

        $sql = "
        SELECT
            h.descripcion AS jornada,
            p.desc_pro AS programa,

            /* foco_detalle (valores netos) */
            fd.cup_fde AS cupos,
            fd.ven_fde AS ventas,
            fd.rein_fde AS reintegros,

            /* Leads con horario EXACTO */
            COUNT(DISTINCT lh.id_lead) AS con_horario,

            /* Leads solo carrera (horario distinto o NULL) */
            COUNT(DISTINCT ls.id_lead) AS solo_carrera

        FROM foco_detalle fd
        INNER JOIN foco f 
            ON f.id_foc = fd.foc_fde
           AND f.emp_foc = fd.emp_fde

        INNER JOIN programa p 
            ON p.cod_pro = fd.prog_fde

        INNER JOIN horario h 
            ON h.id_horario = fd.jorn_fde

        /* Leads con horario correcto */
        LEFT JOIN leads lh
            ON lh.carrera_id = fd.prog_fde
           AND lh.horario_id = fd.jorn_fde
           AND lh.cod_emp = f.emp_foc
           AND lh.estado_leads_id NOT IN (6,7,8)
    ";

        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= " AND lh.user_id = :user_id ";
        }

        $sql .= "
        /* Leads solo carrera */
        LEFT JOIN leads ls
            ON ls.carrera_id = fd.prog_fde
           AND ls.cod_emp = f.emp_foc
           AND ls.estado_leads_id NOT IN (6,7,8)
    ";

        if ($_SESSION['rol'] !== 'Admin') {
            $sql .= " AND ls.user_id = :user_id ";
        }

        $sql .= "
           AND (
                ls.horario_id <> fd.jorn_fde
                OR ls.horario_id IS NULL
           )

        WHERE 
            f.emp_foc = :cod_emp
            AND f.id_foc = :foco

        GROUP BY
            h.descripcion,
            p.desc_pro,
            fd.cup_fde,
            fd.ven_fde,
            fd.rein_fde

        ORDER BY
            h.descripcion,
            p.desc_pro
    ";

        $conn = new Conexion();
        $cn   = $conn->conectar();
        $stmt = $cn->prepare($sql);

        $stmt->bindParam(':cod_emp', $_SESSION["cod_emp"], PDO::PARAM_INT);
        $stmt->bindParam(':foco', $_SESSION["foco"], PDO::PARAM_INT);

        if ($_SESSION['rol'] !== 'Admin') {
            $stmt->bindParam(':user_id', $id_user, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* ================= ARMAR MATRIZ ================= */

        $matriz    = [];
        $jornadas  = [];
        $programas = [];

        foreach ($rows as $r) {

            $jornada  = $r["jornada"];
            $programa = $r["programa"];

            if (!in_array($jornada, $jornadas)) {
                $jornadas[] = $jornada;
            }

            if (!in_array($programa, $programas)) {
                $programas[] = $programa;
            }

            $matriz[$jornada][$programa] = [
                "cupos"      => (int)$r["cupos"],
                "ventas"     => (int)$r["ventas"],
                "reintegros" => (int)$r["reintegros"],
                "con"        => (int)$r["con_horario"],
                "solo"       => (int)$r["solo_carrera"]
            ];
        }

        return [
            "matriz"    => $matriz,
            "jornadas"  => $jornadas,
            "programas" => $programas
        ];
    }

    public static function catalogoFiltroMensaje() {
        $cod_emp = $_SESSION['cod_emp'];
        $foco = $_SESSION['foco'];

    $sql = "
        SELECT
                h.descripcion AS jornada,
                h.id_horario AS id_jornada,
                p.desc_pro AS programa,
                p.cod_pro AS id_programa,
                elh.nombre AS estado,
                elh.id_estado_leads AS id_estado,
                us.nombres AS asesor,
                us.id_user AS id_asesor

            FROM foco_detalle fd
            INNER JOIN foco f 
                ON f.id_foc = fd.foc_fde
            AND f.emp_foc = fd.emp_fde

            INNER JOIN programa p 
                ON p.cod_pro = fd.prog_fde

            INNER JOIN horario h 
                ON h.id_horario = fd.jorn_fde

            /* Leads con horario correcto */
            LEFT JOIN leads lh
                ON lh.carrera_id = fd.prog_fde
            AND lh.horario_id = fd.jorn_fde
            AND lh.cod_emp = f.emp_foc
           

            /* Leads solo carrera (horario distinto o NULL) */
            LEFT JOIN leads ls
                ON ls.carrera_id = fd.prog_fde
            AND ls.cod_emp = f.emp_foc
            
            AND (
                    ls.horario_id <> fd.jorn_fde
                    OR ls.horario_id IS NULL
            )
            
            LEFT JOIN estado_leads elh ON
            
            elh.id_estado_leads = lh.estado_leads_id
            
            LEFT JOIN estado_leads els ON
            
            elh.id_estado_leads = ls.estado_leads_id
            
            LEFT JOIN user us ON
            
            us.id_user = lh.user_id

            WHERE 
                f.emp_foc = $cod_emp
                AND f.id_foc = $foco

            GROUP BY
                h.descripcion,
                p.desc_pro,
                fd.cup_fde,
                fd.ven_fde,
                fd.rein_fde,
                f.nom_foc,
                f.fini_foc,
                f.ffin_foc

            ORDER BY
                h.descripcion,
                p.desc_pro;
    ";

    $conn = new Conexion();
    $pdo = $conn->conectar();
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $carreras = [];
    $horarios = [];
    $estados  = [];
    $asesores = [];

    foreach ($rows as $r) {

        if ($r['id_programa']) {
            $carreras[$r['id_programa']] = [
                'id_programa' => $r['id_programa'],
                'programa'    => $r['programa']
            ];
        }

        if ($r['id_jornada']) {
            $horarios[$r['id_jornada']] = [
                'id_jornada' => $r['id_jornada'],
                'jornada'    => $r['jornada']
            ];
        }

        if ($r['estado']) {
            $estados[$r['estado']] = [
                'id_estado' => $r['id_estado'],
                'estado' => $r['estado']
            ];
        }

        if ($r['asesor']) {
            $asesores[$r['asesor']] = [
                'id_asesor' => $r['id_asesor'],
                'asesor' => $r['asesor']
            ];
        }
    }

    return [
        'carreras' => array_values($carreras),
        'horarios' => array_values($horarios),
        'estados'  => array_values($estados),
        'asesores' => array_values($asesores)
    ];
}

}
