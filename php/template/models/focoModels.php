<?php

class focoModels
{
    public static function agregarFoco($data)
    {
        try {

            $conn = new Conexion();
            $db = $conn->conectar();

            $db->beginTransaction();

            // =========================
            // VALIDAR FOCO
            // =========================
            $sqlCheck = "SELECT id_foc 
                     FROM foco 
                     WHERE id_foc = ? 
                     AND emp_foc = ?";

            $stmtCheck = $db->prepare($sqlCheck);
            $stmtCheck->execute([
                $data["codigoFoco"],
                $_SESSION["cod_emp"]
            ]);

            $foco = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // =========================
            // CREAR FOCO SI NO EXISTE
            // =========================
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

            // =========================
            // INSERTAR DETALLE
            // =========================
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

            // ===================================================
            // VALIDAR SI YA EXISTE EN PRIORIDAD
            // ===================================================
            $sqlExistePrioridad = "SELECT id_pri 
                FROM prioridad 
                WHERE cpr_pri = ?
                AND cho_pri = ?
                AND foc_pri = ?
                AND emp_pri = ?";

            $stmtExistePrioridad = $db->prepare($sqlExistePrioridad);

            $stmtExistePrioridad->execute([
                $data["carrera"],
                $data["horario"],
                $data["codigoFoco"],
                $_SESSION["cod_emp"]
            ]);

            $prioridad = $stmtExistePrioridad->fetch(PDO::FETCH_ASSOC);

            // ===================================================
            // INSERTAR EN PRIORIDAD SI NO EXISTE
            // ===================================================
            if (!$prioridad) {

                // Nombre programa
                $sqlPrograma = "SELECT desc_pro 
                    FROM programa 
                    WHERE cod_pro = ? 
                    LIMIT 1";

                $stmtPrograma = $db->prepare($sqlPrograma);
                $stmtPrograma->execute([$data["carrera"]]);

                $programa = $stmtPrograma->fetch(PDO::FETCH_ASSOC);

                // Nombre jornada
                $sqlHorario = "SELECT descripcion 
                    FROM horario 
                    WHERE id_horario = ? 
                    LIMIT 1";

                $stmtHorario = $db->prepare($sqlHorario);
                $stmtHorario->execute([$data["horario"]]);

                $horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

                // Insert prioridad
                $sqlInsertPrioridad = "INSERT INTO prioridad
                    (
                        cpr_pri,
                        dpr_pri,
                        cho_pri,
                        dho_pri,
                        cup_pri,
                        pri_pri,
                        emp_pri,
                        foc_pri
                    )
                    VALUES (?,?,?,?,?,?,?,?)";

                $stmtInsertPrioridad = $db->prepare($sqlInsertPrioridad);

                $stmtInsertPrioridad->execute([
                    $data["carrera"],
                    $programa["desc_pro"] ?? '',
                    $data["horario"],
                    $horario["descripcion"] ?? '',
                    $data["totalCupoFoco"],
                    1,
                    $_SESSION["cod_emp"],
                    $data["codigoFoco"]
                ]);
            }

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

    public static function listarLeadsFocoResultado(
        $asesor = [],
        $carrera = [],
        $estados = []
    ) {

        $sql = "
        SELECT
            h.descripcion AS jornada,
            p.desc_pro AS programa,
            p.val_pro AS valor_programa,

            fd.cup_fde AS cupos,
            fd.ven_fde AS ventas,
            fd.rein_fde AS reintegros,

            f.nom_foc AS foco,
            f.fini_foc AS fecha_inicio,
            f.ffin_foc AS fecha_fin,

            /* Leads con horario correcto */
            COUNT(DISTINCT CASE 
                WHEN l.estado_leads_id NOT IN (6,7,8)
                AND l.horario_id = fd.jorn_fde
                THEN l.id_lead
            END) AS con_horario,

            /* Leads solo carrera */
            COUNT(DISTINCT CASE 
                WHEN l.estado_leads_id NOT IN (6,7,8)
                AND (l.horario_id <> fd.jorn_fde OR l.horario_id IS NULL)
                THEN l.id_lead
            END) AS solo_carrera,

            /* Ventas estado 6 válidas */
            COUNT(DISTINCT CASE 
                WHEN l.estado_leads_id = 6
                AND l.foco = ?
                AND l.Nfactura IS NOT NULL
                AND l.valorF IS NOT NULL
                AND l.metodoF IS NOT NULL
                AND l.horario_id = fd.jorn_fde
                THEN l.id_lead
            END) AS ventas_estado_6

        FROM foco_detalle fd

        INNER JOIN foco f 
            ON f.id_foc = fd.foc_fde
            AND f.emp_foc = fd.emp_fde

        INNER JOIN programa p 
            ON p.cod_pro = fd.prog_fde

        INNER JOIN horario h 
            ON h.id_horario = fd.jorn_fde

        /* UN SOLO JOIN A LEADS */
        LEFT JOIN leads l
            ON l.carrera_id = fd.prog_fde
            AND l.cod_emp = f.emp_foc

        WHERE 
            f.emp_foc = ?
            AND f.id_foc = ?
        ";

        $params = [
            $_SESSION["foco"],
            $_SESSION["cod_emp"],
            $_SESSION["foco"]
        ];

        /* ============================
        FILTRO POR ASESOR
        ============================ */
        if (!empty($asesor)) {

            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $sql .= " AND l.user_id IN ($placeholders)";
            $params = array_merge($params, $asesor);
        } else {

            if ($_SESSION['rol'] !== 'Admin') {
                $sql .= " AND l.user_id = ?";
                $params[] = $_SESSION['user_id'];
            }
        }

        /* ============================
        FILTRO POR ESTADO
        ============================ */
        if (!empty($estados)) {

            $placeholders = implode(",", array_fill(0, count($estados), "?"));

            $sql .= "
        AND l.estado_leads_id IN (
            SELECT id_estado_leads 
            FROM estado_leads 
            WHERE nombre IN ($placeholders)
        )";

            $params = array_merge($params, $estados);
        }

        /* ============================
        FILTRO POR CARRERA
        ============================ */
        if (!empty($carrera)) {

            $placeholders = implode(",", array_fill(0, count($carrera), "?"));
            $sql .= " AND p.desc_pro IN ($placeholders)";
            $params = array_merge($params, $carrera);
        }

        /* ============================
        GROUP BY
        ============================ */
        $sql .= "
        GROUP BY
            h.descripcion,
            p.desc_pro,
            p.val_pro,
            fd.cup_fde,
            fd.ven_fde,
            fd.rein_fde,
            f.nom_foc,
            f.fini_foc,
            f.ffin_foc

        ORDER BY
            h.descripcion,
            p.desc_pro
        ";

        $conn = new Conexion();
        $pdo = $conn->conectar();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function actulizarFocoDetalle($data)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        try {

            $conectar->beginTransaction();

            // ============================================
            // ACTUALIZAR FOCO DETALLE
            // ============================================
            $sql = "UPDATE foco_detalle fd 
                INNER JOIN programa p ON p.cod_pro = fd.prog_fde 
                INNER JOIN horario h ON h.id_horario = fd.jorn_fde 
                SET 
                    fd.cup_fde = ?, 
                    fd.rein_fde = ?, 
                    fd.ven_fde = ? 
                WHERE h.descripcion = ? 
                AND p.desc_pro = ?";

            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(1, $data['ventas']);
            $stmt->bindParam(2, $data['reintegros']);
            $stmt->bindParam(3, $data['cupos']);
            $stmt->bindParam(4, $data['jornada']);
            $stmt->bindParam(5, $data['programa']);

            $stmt->execute();

            // ============================================
            // ACTUALIZAR PRIORIDAD
            // ============================================
            $sqlPrioridad = "UPDATE prioridad pri
                INNER JOIN programa p ON p.cod_pro = pri.cpr_pri
                INNER JOIN horario h ON h.id_horario = pri.cho_pri
                SET pri.cup_pri = ?
                WHERE h.descripcion = ?
                AND p.desc_pro = ?
                AND pri.emp_pri = ?";

            $stmtPrioridad = $conectar->prepare($sqlPrioridad);

            $stmtPrioridad->execute([
                $data['cupos'],
                $data['jornada'],
                $data['programa'],
                $_SESSION["cod_emp"]
            ]);

            $conectar->commit();

            return [
                'status' => 'success',
                'message' => 'Foco detalle actualizado'
            ];
        } catch (Exception $e) {

            if ($conectar->inTransaction()) {
                $conectar->rollBack();
            }

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public static function eliminarFocoDetalle($data)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        try {

            $conectar->beginTransaction();

            // ============================================
            // ELIMINAR DE FOCO DETALLE
            // ============================================
            $sql = "DELETE fd 
            FROM foco_detalle fd 
            INNER JOIN programa p ON p.cod_pro = fd.prog_fde 
            INNER JOIN horario h ON h.id_horario = fd.jorn_fde 
            WHERE h.descripcion = ? 
            AND p.desc_pro = ?";

            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(1, $data['jornada']);
            $stmt->bindParam(2, $data['programa']);

            $stmt->execute();

            // ============================================
            // ELIMINAR TAMBIEN EN PRIORIDAD
            // ============================================
            $sqlPrioridad = "DELETE pri
                FROM prioridad pri
                INNER JOIN programa p ON p.cod_pro = pri.cpr_pri
                INNER JOIN horario h ON h.id_horario = pri.cho_pri
                WHERE h.descripcion = ?
                AND p.desc_pro = ?
                AND pri.emp_pri = ?";

            $stmtPrioridad = $conectar->prepare($sqlPrioridad);

            $stmtPrioridad->execute([
                $data['jornada'],
                $data['programa'],
                $_SESSION["cod_emp"]
            ]);

            $conectar->commit();

            return [
                'status' => 'success',
                'message' => 'Foco detalle eliminado'
            ];
        } catch (Exception $e) {

            if ($conectar->inTransaction()) {
                $conectar->rollBack();
            }

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
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

    public static function catalogoFiltroMensaje()
    {

        $cod_emp = $_SESSION['cod_emp'];
        $foco    = $_SESSION['foco'];

        $conn = new Conexion();
        $pdo  = $conn->conectar();

        /* ==========================
       CARRERAS
    ========================== */
        $sqlCarreras = "
        SELECT DISTINCT
            p.cod_pro   AS id_programa,
            p.desc_pro  AS programa
        FROM programa p
        WHERE p.emp_pro = $cod_emp
        ORDER BY p.desc_pro
    ";

        $carreras = $pdo->query($sqlCarreras)->fetchAll(PDO::FETCH_ASSOC);


        /* ==========================
       HORARIOS
    ========================== */
        $sqlHorarios = "
        SELECT DISTINCT
            h.id_horario AS id_jornada,
            h.descripcion AS jornada
        FROM horario h
        ORDER BY h.descripcion
    ";

        $horarios = $pdo->query($sqlHorarios)->fetchAll(PDO::FETCH_ASSOC);


        /* ==========================
       ESTADOS (TODOS)
    ========================== */
        $sqlEstados = "
        SELECT
            id_estado_leads AS id_estado,
            nombre          AS estado
        FROM estado_leads
        ORDER BY nombre
    ";

        $estados = $pdo->query($sqlEstados)->fetchAll(PDO::FETCH_ASSOC);


        /* ==========================
       ASESORES (Sandra y Yalile)
    ========================== */
        $sqlAsesores = "
        SELECT DISTINCT
            u.id_user AS id_asesor,
            u.nombres AS asesor,
            u.url
        FROM user u
        WHERE u.cod_emp = $cod_emp
          AND u.rol_id IN ('1','3')
        ORDER BY u.nombres;
        ";

        $asesores = $pdo->query($sqlAsesores)->fetchAll(PDO::FETCH_ASSOC);


        /* ==========================
       RETURN (NO CAMBIA)
    ========================== */
        return [
            'carreras' => $carreras,
            'horarios' => $horarios,
            'estados'  => $estados,
            'asesores' => $asesores
        ];
    }

    public static function consultarFocoFecha()
    {
        $sql = "SELECT ffin_foc FROM foco WHERE id_foc = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $_SESSION['foco']);
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function mdlCerrarFoco($focoActual, $empresa)
    {

        try {

            $conn = new Conexion();
            $pdo = $conn->conectar();

            // 🔹 INICIAR TRANSACCIÓN
            $pdo->beginTransaction();

            // 1. BUSCAR EL SIGUIENTE FOCO (MEJOR QUE +1)
            $stmt = $pdo->prepare("
                SELECT id_foc 
                FROM foco 
                WHERE id_foc > ? 
                ORDER BY id_foc ASC 
                LIMIT 1
            ");
            $stmt->execute([$focoActual]);
            $nuevoFoco = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$nuevoFoco) {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "message" => "No existe un foco siguiente, debes crearlo primero"
                ];
            }

            $idNuevoFoco = $nuevoFoco['id_foc'];

            // 2. VALIDAR QUE EL FOCO TENGA FECHA (opcional pero pro)
            $stmt = $pdo->prepare("SELECT ffin_foc FROM foco WHERE id_foc = ?");
            $stmt->execute([$idNuevoFoco]);
            $focoData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$focoData) {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "message" => "El foco siguiente no tiene configuración válida"
                ];
            }

            // 3. ACTUALIZAR EMPRESA
            $stmt = $pdo->prepare("UPDATE empresa SET foco = ? WHERE id_emp = ?");
            $ok = $stmt->execute([$idNuevoFoco, $empresa]);

            if (!$ok) {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "message" => "Error actualizando la empresa"
                ];
            }

            // 🔹 CONFIRMAR
            $pdo->commit();

            return [
                "status" => "success",
                "nuevo_foco" => $idNuevoFoco
            ];
        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return [
                "status" => "error",
                "message" => "Error interno: " . $e->getMessage()
            ];
        }
    }
}
