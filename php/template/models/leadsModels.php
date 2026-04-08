<?php

class LeadsModels
{
    public static function agregarLeads($data, $id, $id_user, $id_estado_leads)
    {
        $sql = "INSERT INTO leads (user_id, cliente_id, info_adicional, carrera_id, horario_id, interes_id, medio_id, fuente_id, campana_id, accion_id, departamento_id, barrio_id, ciudad_id, estado_leads_id, observaciones, url_origen, cod_emp, utm_source, utm_medium, utm_campaign) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $url = $data['origen_url'] ?? null;
        $cod_emp = $data['cod_emp'] ?? $_SESSION['cod_emp'] ?? null;
        $sourceField   = !empty($data['sourceField'])   ? $data['sourceField']   : "directo";
        $mediumField   = !empty($data['mediumField'])   ? $data['mediumField']   : "ninguno";
        $campaignField = !empty($data['campaignField']) ? $data['campaignField'] : "general";

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $id);
        $stmt->bindParam(3, $data["infoLeads"]);
        $stmt->bindParam(4, $data["carrera"]);
        $stmt->bindParam(5, $data["horario"]);
        $stmt->bindParam(6, $data["interes"]);
        $stmt->bindParam(7, $data["medio"]);
        $stmt->bindParam(8, $data["fuente"]);
        $stmt->bindParam(9, $data["campana"]);
        $stmt->bindParam(10, $data["accion"]);
        $stmt->bindParam(11, $data["departamento"]);
        $stmt->bindParam(12, $data["barrio"]);
        $stmt->bindParam(13, $data["ciudad"]);
        $stmt->bindParam(14, $id_estado_leads);
        $stmt->bindParam(15, $data["observacionLeads"]);
        $stmt->bindParam(16, $url);
        $stmt->bindParam(17, $cod_emp);
        $stmt->bindParam(18, $sourceField);
        $stmt->bindParam(19, $mediumField);
        $stmt->bindParam(20, $campaignField);

        if ($stmt->execute()) {
            $ultimoId = $conectar->lastInsertId();
            return $ultimoId;
        }

        return "error";
    }

    public static function actualizarLeads($data, $id, $id_user, $id_estado_leads)
    {
        $sql = "UPDATE leads SET user_id = ?, cliente_id = ?, info_adicional = ?, carrera_id = ?, horario_id = ?, interes_id = ?, medio_id = ?, fuente_id = ?, campana_id = ?, accion_id = ?, departamento_id = ?, barrio_id = ?, ciudad_id = ?, estado_leads_id = ?, observaciones = ?, cod_emp = ?, url_origen = ? WHERE cliente_id = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $id);
        $stmt->bindParam(3, $data["infoLeads"]);
        $stmt->bindParam(4, $data["carrera"]);
        $stmt->bindParam(5, $data["horario"]);
        $stmt->bindParam(6, $data["interes"]);
        $stmt->bindParam(7, $data["medio"]);
        $stmt->bindParam(8, $data["fuente"]);
        $stmt->bindParam(9, $data["campana"]);
        $stmt->bindParam(10, $data["accion"]);
        $stmt->bindParam(11, $data["departamento"]);
        $stmt->bindParam(12, $data["barrio"]);
        $stmt->bindParam(13, $data["ciudad"]);
        $stmt->bindParam(14, $id_estado_leads);
        $stmt->bindParam(15, $data["observacionLeads"]);
        $stmt->bindParam(16, $_SESSION['cod_emp'] ?? $data['cod_emp']);
        $stmt->bindParam(17, $data["origen_url"]);
        $stmt->bindParam(18, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function getLeads($texto = "", $asesor = [], $carreras = [], $horario = [], $interes = [], $medio = [], $fuente = [], $campana = [], $accion = [], $departamento = [], $ciudad = [], $barrio = [], $estados = [], $fecha_inicio = "", $fecha_fin = [])
    {
        $sql = "SELECT 
                l.*, 
                c.nombres, 
                c.apellidos, 
                c.email, 
                c.telefono_principal, 
                ci.desc_ciu AS ciudad, 
                h.descripcion AS horario,
                p.desc_pro, 
                e.nombre AS estado,
                CONCAT(u.nombres, ' ', u.apellidos) AS nombreAsesor
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN programa p ON l.carrera_id = p.cod_pro 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            LEFT JOIN user u ON l.user_id = u.id_user
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            WHERE l.cod_emp = ?
            AND (
                e.nombre != 'Matriculado'
                OR (e.nombre = 'Matriculado' AND l.foco = ?)
            )
            ";

        $params = [$_SESSION['cod_emp'],$_SESSION['foco']];

        /* ===========================
        FILTRO POR ROL (Sincronizado con listarLeads)
    ============================ */
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin' && $texto === "" && empty($asesor)) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }

        /* ===========================
        FILTRO POR TEXTO (Igual a listarLeads)
    ============================ */
        if ($texto !== "") {
            $sql .= " AND (c.nombres LIKE ? OR c.apellidos LIKE ? OR c.email LIKE ? OR c.telefono_principal LIKE ?)";
            $buscar = "%$texto%";
            $params = array_merge($params, array_fill(0, 4, $buscar));
        }

        /* ===========================
        FILTROS DE ARRAYS (Mapeo idéntico)
    ============================ */
        $filtros = [
            'l.user_id' => $asesor,
            'p.desc_pro' => $carreras,
            'l.interes_id' => $interes,
            'l.medio_id' => $medio,
            'l.fuente_id' => $fuente,
            'l.campana_id' => $campana,
            'l.accion_id' => $accion,
            'l.departamento_id' => $departamento,
            'l.ciudad_id' => $ciudad,
            'l.barrio_id' => $barrio,
            'e.nombre' => $estados
        ];

        foreach ($filtros as $columna => $valores) {
            if (!empty($valores)) {
                $placeholders = implode(",", array_fill(0, count($valores), "?"));
                $sql .= " AND $columna IN ($placeholders)";
                $params = array_merge($params, $valores);
            }
        }

        /* ===========================
        FILTRO POR HORARIO (Sincronizado)
    ============================ */
        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND (h.descripcion IN ($placeholders) OR h.id_horario IN ($placeholders))";
            $params = array_merge($params, $horario, $horario);
        }

        /* ===========================
        FILTRO POR FECHA
    ============================ */
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        $sql .= " ORDER BY l.fecha_creacion DESC";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateEstado($idLead, $idEstado)
    {
        $sql = "UPDATE leads SET estado_leads_id = ? WHERE id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$idEstado, $idLead]);
        return "ok";
    }

    public static function ingresarMatricula($idLead, $dato)
    {
        $sql = "UPDATE leads SET Nfactura = ?, valorF = ?, metodoF = ?, foco = ? WHERE id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $dato['facturaN']);
        $stmt->bindParam(2, $dato['valorF']);
        $stmt->bindParam(3, $dato['metodoP']);
        $stmt->bindParam(4, $_SESSION['foco']);
        $stmt->bindParam(5, $idLead);
        $stmt->execute();
        return "ok";
    }

    public static function listarLeads($texto = "", $asesor = [], $carreras = [], $horario = [], $interes = [], $medio = [], $fuente = [], $campana = [], $accion = [], $departamento = [], $ciudad = [], $barrio = [], $estados = [], $fecha_inicio = "", $fecha_fin = "", $tipo = '', $estadosPer = [])
    {
        $sql = "SELECT 
                l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, 
                p.desc_pro, ci.desc_ciu AS ciudad, l.fecha_creacion, 
                u.nombres AS nombreAsesor, u.apellidos AS apellidoAsesor,
                h.descripcion AS horario, e.nombre AS estado
            FROM leads l 
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN programa p ON p.cod_pro = l.carrera_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN user u ON u.id_user = l.user_id 
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            LEFT JOIN motivo_estado_leads m ON m.id_mot = l.est_motivo
            LEFT JOIN motivo_perdido mt ON mt.id_per = m.per_id
            WHERE l.cod_emp = ?
            AND (
                e.nombre != 'Matriculado'
                OR (e.nombre = 'Matriculado' AND l.foco = ?)
            )
            ";

        $params = [$_SESSION['cod_emp'],$_SESSION['foco']];

        // Seguridad por Rol
        if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin' && $texto === "" && empty($asesor)) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }

        // Filtros de Texto y Arrays
        if ($texto !== "") {
            $sql .= " AND (c.nombres LIKE ? OR c.apellidos LIKE ? OR c.email LIKE ? OR c.telefono_principal LIKE ?)";
            $buscar = "%$texto%";
            $params = array_merge($params, array_fill(0, 4, $buscar));
        }

        // Mapeo de filtros array (Key = Columna en DB, Value = Variable)
        $filtros = [
            'l.user_id' => $asesor,
            'p.desc_pro' => $carreras,
            'l.interes_id' => $interes,
            'l.medio_id' => $medio,
            'l.fuente_id' => $fuente,
            'l.campana_id' => $campana,
            'l.accion_id' => $accion,
            'l.departamento_id' => $departamento,
            'l.ciudad_id' => $ciudad,
            'l.barrio_id' => $barrio,
            'e.nombre' => $estados,
            'mt.desc_per' => $estadosPer
        ];

        foreach ($filtros as $columna => $valores) {
            if (!empty($valores)) {
                $placeholders = implode(",", array_fill(0, count($valores), "?"));
                $sql .= " AND $columna IN ($placeholders)";
                $params = array_merge($params, $valores);
            }
        }

        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND (h.descripcion IN ($placeholders) OR h.id_horario IN ($placeholders))";
            $params = array_merge($params, $horario, $horario);
        }

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        $sql .= " ORDER BY l.fecha_creacion DESC";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarLeadsId($id)
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, c.acudiente, c.tel_acudiente, ci.desc_ciu AS ciudad, l.fecha_creacion, f.desc_fue, c.direccion, c.identificacion, el.nombre AS estado_leads, es.des_seguimiento, pro.desc_pro, pro.val_pro, em.nom_emp, h.descripcion AS horario, i.nombre AS interes, m.desc_med, cam.nombre AS campana, acc.nombre AS accion, dep.desc_dep, brr.desc_brr, l.observaciones, u.nombres AS nombreAsesor, u.apellidos AS apellidoAsesor FROM leads l LEFT JOIN cliente c ON c.id_cliente = l.cliente_id LEFT JOIN horario h ON h.id_horario = l.horario_id LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id LEFT JOIN user u ON u.id_user = l.user_id LEFT JOIN fuente1 f ON f.cod_fue = l.fuente_id LEFT JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id LEFT JOIN estado_seguimiento es ON es.id_seguimiento = l.estadoLeadsSeguimiento LEFT JOIN programa pro ON pro.cod_pro = l.carrera_id LEFT JOIN empresa em ON em.id_emp = pro.emp_pro LEFT JOIN interes i ON i.id_interes = l.interes_id LEFT JOIN medio1 m ON m.cod_med = l.medio_id LEFT JOIN campana cam ON cam.id_campana = l.campana_id LEFT JOIN accion acc ON acc.id_accion = l.accion_id LEFT JOIN departamento dep ON dep.cod_dep = l.departamento_id LEFT JOIN barrio brr ON brr.id_barrio = l.barrio_id WHERE l.id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function utm_campaign()
    {
        $sql = "SELECT 
            utm_campaign AS campaign,
            COUNT(*) AS total
        FROM leads
        WHERE utm_campaign IS NOT NULL
          AND utm_campaign <> '' AND cod_emp = ?
        GROUP BY utm_campaign
        ORDER BY total DESC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$_SESSION['cod_emp']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function nuevo_leads_por_dia($fechaActual)
    {
        $sql = "SELECT COUNT(*) AS total FROM `leads` WHERE fecha_creacion = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$fechaActual]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reporte_leads_gestionado()
    {
        try {
            $sql = "SELECT 
                    u.nombres AS Gestor,
                    COUNT(DISTINCT l.id_lead) AS Total_Leads_Asignados,
                    COUNT(DISTINCT CASE 
                        WHEN l.accion_id IS NOT NULL 
                        THEN l.id_lead 
                    END) AS Total_Leads_Gestionados,
                    COUNT(l.id_log) AS Total_Gestiones_Realizadas,
                    ROUND(
                        (COUNT(DISTINCT CASE WHEN l.accion_id IS NOT NULL THEN l.id_lead END) * 100.0 / 
                        NULLIF(COUNT(DISTINCT l.id_lead), 0)), 
                        2
                    ) AS Porcentaje_Gestion,
                    MIN(l.fecha_movimiento) AS Primera_Gestion,
                    MAX(l.fecha_movimiento) AS Ultima_Gestion,
                    COUNT(DISTINCT DATE(l.fecha_movimiento)) AS Dias_Activos
                FROM log_lead l 
                INNER JOIN user u ON u.id_user = l.user_modifico
                WHERE l.fecha_movimiento >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                    AND l.cod_emp = ?
                GROUP BY l.user_modifico, u.nombres
                ORDER BY Total_Leads_Gestionados DESC";

            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
            $stmt->execute([$_SESSION['cod_emp']]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'data' => $resultado,
                    'message' => 'Datos obtenidos correctamente'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al obtener los datos'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public static function reporteLeadsBarra()
    {
        $sql = "SELECT
                    DATE_FORMAT(fecha_creacion, '%b') AS mes,
                    MONTH(fecha_creacion) AS mes_num,
                    SUM(CASE WHEN estado_leads_id = 1 THEN 1 ELSE 0 END) AS nuevos,
                    SUM(CASE WHEN estado_leads_id <> 1 THEN 1 ELSE 0 END) AS otros
                FROM leads
                WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 9 MONTH) AND cod_emp = ?
                GROUP BY mes, mes_num
                ORDER BY mes_num;";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$_SESSION['cod_emp']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reporteLeadsPastelMotivo()
    {
        $sql = "SELECT 
            u.id_user,
            u.nombres AS asesor,
            COALESCE(mt.desc_per, 'Otros') AS estado,
            COUNT(*) AS cantidad
        FROM motivo_estado_leads m
        LEFT JOIN motivo_perdido mt 
            ON mt.id_per = m.per_id
        INNER JOIN leads l 
            ON l.est_motivo = m.id_mot
        INNER JOIN user u 
            ON u.id_user = l.user_id
        WHERE l.cod_emp = ?
        GROUP BY u.nombres, mt.desc_per
        ORDER BY estado, asesor;";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$_SESSION['cod_emp']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reporteLeadsPastel()
    {
        $sql = "SELECT e.nombre AS estado, COUNT(*) AS cantidad
        FROM leads l INNER JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
        WHERE l.cod_emp = ?
        GROUP BY estado;";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$_SESSION['cod_emp']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reporteLeadsBarraMatriculado()
    {
        $sql = "SELECT DATE_FORMAT(fecha_creacion, '%b') AS mes, MONTH(fecha_creacion) AS mes_num, SUM(CASE WHEN estado_leads_id = 6 THEN 1 ELSE 0 END) AS matriculados FROM leads 
        WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 9 MONTH) AND cod_emp = ? GROUP BY mes, mes_num ORDER BY mes_num;";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$_SESSION['cod_emp']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerAsesorConMenosLeads($data)
    {
        $cod_emp = $data['cod_emp'] ?? $_SESSION['cod_emp'] ?? $_GET['cod_emp'] ?? $_POST['cod_emp'] ?? null;

        $sql = "SELECT l.user_id, COUNT(*) AS total FROM leads l INNER JOIN user u ON u.id_user = l.user_id INNER JOIN user_role ur ON ur.id_rol = u.rol_id WHERE l.cod_emp = '$cod_emp' AND ur.activo = 1 GROUP BY l.user_id ORDER BY total ASC LIMIT 1";

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si NO hay leads aún, tomar cualquier usuario asesor
        if (!$res) {
            $sql2 = "SELECT u.id_user AS user_id FROM user u INNER JOIN user_role r ON u.rol_id = r.id_rol WHERE r.nombre_rol like '%asesor%' AND u.cod_emp = '$cod_emp' AND r.activo = 1";
            $stmt2 = $conectar->prepare($sql2);
            $stmt2->execute();
            return $stmt2->fetch(PDO::FETCH_ASSOC);
        }

        return $res;
    }

    public static function cambiarAsesor($id_lead, $user_id)
    {
        $sql = "UPDATE leads SET user_id = ? WHERE id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $id_lead);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function actualizarColumnasLeads($leadId, $columna, $valor)
    {
        $sql = "UPDATE leads l LEFT JOIN cliente c ON c.id_cliente = l.cliente_id SET $columna = ? WHERE id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $valor);
        $stmt->bindParam(2, $leadId);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function obtenerResumenHorarios($empresa = null, $asesor = null, $programa = null, $horario = null, $estado = null, $fecha_inicio = null, $fecha_fin = null)
    {
        try {
            $sql = "CALL obtener_resumen_horarios(?,?,?,?,?,?,?)";

            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(1, $empresa);
            $stmt->bindParam(2, $asesor);
            $stmt->bindParam(3, $programa);
            $stmt->bindParam(4, $horario);
            $stmt->bindParam(5, $estado);
            $stmt->bindParam(6, $fecha_inicio);
            $stmt->bindParam(7, $fecha_fin);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            while ($stmt->nextRowset()) {;
            }

            return $result;
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public static function consultarClienteLeads($valor)
    {
        $sql = "SELECT CONCAT(c.nombres, c.apellidos) AS nombre, c.telefono_principal, c.acudiente, c.tel_acudiente, l.carrera_id, l.horario_id, l.id_lead, l.cod_emp, l.cliente_id FROM `leads` l INNER JOIN cliente c ON c.id_cliente = l.cliente_id WHERE c.telefono_principal = ? LIMIT 1";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $valor);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function consultarClienteLeadsTele($valor)
    {
        $conn = new Conexion();
        $conectar = $conn->conectar();

        // Agregamos comodines para LIKE
        $valorLike = "%$valor%";

        // 🔹 1️⃣ Buscar primero en leads + cliente
        $sql1 = "SELECT 
                CONCAT(c.nombres, ' ', c.apellidos) AS nombre,
                c.telefono_principal,
                c.acudiente,
                c.tel_acudiente,
                l.carrera_id,
                l.horario_id,
                l.id_lead,
                l.cod_emp,
                l.cliente_id
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id
            WHERE c.telefono_principal LIKE ?
            LIMIT 1";

        $stmt1 = $conectar->prepare($sql1);
        $stmt1->bindParam(1, $valorLike, PDO::PARAM_STR);
        $stmt1->execute();

        $resultado = $stmt1->fetch(PDO::FETCH_ASSOC);

        // ✅ Si existe en leads, retornamos
        if ($resultado) {
            return [
                "origen" => "leads",
                "data" => $resultado
            ];
        }

        // 🔹 2️⃣ Si no existe, buscar en telemercadeo
        $sql2 = "SELECT 
                CONCAT(nom_con, ' ', ape_con) AS nombre,
                telefono AS telefono_principal,
                cod_car_con AS carrera_id,
                cod_hor_con AS horario_id,
                estado_lead_id AS estado_lead,
                user_id,
                email,
                dir_con AS dire
            FROM telemercadeo
            WHERE telefono LIKE ?
            LIMIT 1";

        $stmt2 = $conectar->prepare($sql2);
        $stmt2->bindParam(1, $valorLike, PDO::PARAM_STR);
        $stmt2->execute();

        $resultadoTele = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($resultadoTele) {
            return [
                "origen" => "telemercadeo",
                "data" => $resultadoTele
            ];
        }

        // ❌ No encontrado en ninguna
        return false;
    }

    public static function actualizarLeadYCliente($data)
    {
        $sql = "UPDATE leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id
            SET
                c.acudiente      = :acudiente,
                c.tel_acudiente  = :tel_acudiente,
                l.carrera_id     = :carrera,
                l.horario_id     = :horario,
                l.user_id        = :user_id,
                l.estado_leads_id = 3
            WHERE l.id_lead = :lead_id";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);

        return $stmt->execute([
            ":acudiente"     => $data["acudiente"],
            ":tel_acudiente" => $data["tel_acudiente"],
            ":carrera"       => $data["carrera"],
            ":horario"       => $data["horario"],
            ":lead_id"       => $data["lead_id"],
            ":user_id"       => $data["user_id"]
        ]);
    }

    public static function registrarObservacion($data)
    {
        $sql = "INSERT INTO rst_frm
            (lead_id, obs_rst, tipo_trans_id, user_id, cod_emp, bd_rst)
            VALUES (:lead, :obs, :tip_tras, :user, :cod_emp, :bd_rst)";

        $conn = new Conexion();
        $stmt = $conn->conectar()->prepare($sql);

        return $stmt->execute([
            ":lead"    => $data["lead_id"],
            ":obs"     => $data["obs"],
            ":tip_tras"     => $data["tip_tras"],
            ":user"    => $data["usuario"],
            ":cod_emp" => $data["cod_emp"],
            ":bd_rst" => $data["bd_rst"]
        ]);
    }

    public static function listarReporteRst($texto = "", $asesor = [])
    {
        // 1. Base de la consulta (SIN el ";" al final y SIN el GROUP BY aquí)
        $codEmp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];
        $sql = "
        SELECT
            r.cod_rst,
            r.fecha,
            r.obs_rst,
            l.id_lead,
            CONCAT(c.nombres, ' ', c.apellidos) AS cliente_nombre,
            c.telefono_principal AS cliente_telefono,
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor_nombre,
            CONCAT(ul.nombres, ' ', ul.apellidos) AS asesor_nombre_lead,
            el.nombre AS estado_leads,
            r.cod_emp,
            tp.des_tipo_trans AS tipo_nom,
            n.desc_not AS nota
        FROM rst_frm r
        LEFT JOIN leads l ON l.id_lead = r.lead_id
        LEFT JOIN cliente c ON c.id_cliente = l.cliente_id
        LEFT JOIN user u ON u.id_user = r.user_id
        LEFT JOIN user ul ON ul.id_user = l.user_id
        LEFT JOIN tipo_trans tp ON tp.id_tipo_trans = r.tipo_trans_id
        LEFT JOIN nota n ON n.id_lead = l.id_lead
        INNER JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id
        WHERE r.cod_emp = ? AND r.user_id = 17
        AND (
            el.nombre != 'Matriculado'
            OR (el.nombre = 'Matriculado' AND l.foco = ?)
        )
    ";

        $params = [$codEmp,$_SESSION['foco']];

        // 2. Filtro por Texto
        if ($texto !== "") {
            $sql .= " AND (c.nombres LIKE ? OR c.apellidos LIKE ? OR c.telefono_principal LIKE ?)";
            $buscar = "%$texto%";
            array_push($params, $buscar, $buscar, $buscar);
        }

        // 3. Filtro por Asesor (Corregido)
        if (!empty($asesor)) {
            // Aseguramos que los valores sean tratados como placeholders
            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $sql .= " AND l.user_id IN ($placeholders)";
            $params = array_merge($params, $asesor);
        }

        // 4. Agrupación y Orden (DEBEN ir al final de todo)
        $sql .= " GROUP BY r.cod_rst, r.fecha, r.obs_rst";
        $sql .= " ORDER BY r.fecha DESC"; // Usamos r.fecha ya que l.fecha_creacion no está en el SELECT

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarReporteRstDia($mes = null, $anio = null)
    {
        // 🔹 Si no vienen mes o año, se calculan automáticamente
        $mes  = $mes  ?? date('m');
        $anio = $anio ?? date('Y');

        $codEmp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];

        $conn = new Conexion();
        $pdo  = $conn->conectar();

        /* =====================================================
        CONSULTA 1 → LEADS ASIGNADOS POR DÍA Y ASESOR
        ====================================================== */
        $sqlPorDia = "
        SELECT
            DAY(r.fecha) AS dia,
            MONTH(r.fecha) AS mes,
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor,
            CONCAT(ur.nombres, ' ', ur.apellidos) AS asesorRTS,

            COUNT(*) AS total,

            SUM(
                CASE 
                    WHEN tp.id_tipo_trans IS NOT NULL THEN 1
                    ELSE 0
                END
            ) AS tipo,

            tp.des_tipo_trans AS tipo_nom

        FROM rst_frm r
        LEFT JOIN user ur 
            ON ur.id_user = r.user_id
        LEFT JOIN leads l 
            ON r.lead_id = l.id_lead
        LEFT JOIN user u 
            ON u.id_user = l.user_id
        LEFT JOIN tipo_trans tp 
            ON tp.id_tipo_trans = r.tipo_trans_id

        WHERE r.cod_emp = ? AND r.user_id = 17
        AND (
            l.estado_leads_id != '6'
            OR (l.estado_leads_id = '6' AND l.foco = ?)
        )       

        GROUP BY
            dia,
            asesor,
            asesorRTS

        ORDER BY mes DESC;
        ";
        /*AND MONTH(r.fecha) = ?
        AND YEAR(r.fecha) = ? 
        , $mes, $anio*/
        $stmtDia = $pdo->prepare($sqlPorDia);
        $stmtDia->execute([$codEmp,$_SESSION['foco']]);
        $porDia = $stmtDia->fetchAll(PDO::FETCH_ASSOC);

        /* =====================================================
        CONSULTA 2 → LEADS POR ESTADO Y ASESOR
        ====================================================== */
        $sqlPorEstado = "
        SELECT
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor,
            el.nombre AS estado,
            el.ord_eld AS id,

            COUNT(*) AS total

            FROM rst_frm r
            LEFT JOIN leads l 
                ON r.lead_id = l.id_lead
            LEFT JOIN user u 
                ON u.id_user = l.user_id
            LEFT JOIN estado_leads el 
                ON el.id_estado_leads = l.estado_leads_id

            WHERE r.cod_emp = ? AND r.user_id = 17
            AND (
                el.nombre != 'Matriculado'
                OR (el.nombre = 'Matriculado' AND l.foco = ?)
            )
            GROUP BY
                asesor,
                el.id_estado_leads,
                el.nombre,
                el.ord_eld

            ORDER BY el.ord_eld ASC;

            ";
        /*AND MONTH(r.fecha) = ?
            AND YEAR(r.fecha) = ? 
            , $mes, $anio*/
        $stmtEstado = $pdo->prepare($sqlPorEstado);
        $stmtEstado->execute([$codEmp,$_SESSION['foco']]);
        $porEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);

        /* =====================================================
        RETORNO FINAL (LISTO PARA JS / EXCEL)
        ====================================================== */
        return [
            'mes'       => $mes,
            'anio'      => $anio,
            'porDia'    => $porDia,
            'porEstado' => $porEstado
        ];
    }

    public static function listarReporteLeadDia($mes = null, $anio = null, $asesor = [], $carreras = [], $horario = [], $estados = [], $fecha_inicio = "", $fecha_fin = "")
    {
        // 🔹 Configuración inicial de fechas
        $mes  = $mes  ?? date('m');
        $anio = $anio ?? date('Y');
        $codEmp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];

        $conn = new Conexion();
        $pdo  = $conn->conectar();

        // Consulta base con los JOINs necesarios para filtrar
        $sqlPorEstado = "
        SELECT
            u.id_user,
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor,
            el.nombre AS estado,
            el.ord_eld AS id,
            COUNT(l.id_lead) AS total
        FROM leads l
        INNER JOIN cliente c ON c.id_cliente = l.cliente_id
        LEFT JOIN user u ON u.id_user = l.user_id
        LEFT JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id
        LEFT JOIN programa p ON p.cod_pro = l.carrera_id
        WHERE l.cod_emp = ?
        AND (
            el.nombre != 'Matriculado'
            OR (el.nombre = 'Matriculado' AND l.foco = ?)
        )
    ";

        $params = [$codEmp, $_SESSION['foco']];

        /* =====================================================
       SEGURIDAD POR ROL (Sincronizado)
    ====================================================== */
        /*if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin' && empty($asesor)) {
            $sqlPorEstado .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }*/

        /* =====================================================
       FILTROS DINÁMICOS (Mapeo idéntico)
    ====================================================== */
        $filtros = [
            'l.user_id'    => $asesor,
            'p.desc_pro'   => $carreras,
            'l.horario_id'   => $horario,
            'el.nombre'    => $estados
        ];

        foreach ($filtros as $columna => $valores) {
            if (!empty($valores)) {
                $placeholders = implode(",", array_fill(0, count($valores), "?"));
                $sqlPorEstado .= " AND $columna IN ($placeholders)";
                $params = array_merge($params, $valores);
            }
        }

        /* ===========================
       AGRUPACIÓN Y ORDEN
    ============================ */
        $sqlPorEstado .= "
        GROUP BY 
            asesor, 
            el.id_estado_leads, 
            el.nombre, 
            el.ord_eld
        ORDER BY el.ord_eld ASC
    ";

        $stmtEstado = $pdo->prepare($sqlPorEstado);
        $stmtEstado->execute($params);
        $porEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);

        return [
            'mes'       => $mes,
            'anio'      => $anio,
            'porEstado' => $porEstado
        ];
    }

    public static function listarLeadsFiltradosMensaje($carrera, $horario, $estado, $asesor, $numero)
    {

        $sql = "
        SELECT
            l.id_lead,
            c.nombres AS cliente,
            c.telefono_principal AS numero,
            u.nombres AS asesor,
            p.desc_pro AS carrera,
            j.desc_jor AS jornada
        FROM leads l
        INNER JOIN cliente c ON c.id_cliente = l.cliente_id
        INNER JOIN user u ON u.id_user = l.user_id
        INNER JOIN programa p ON p.cod_pro = l.carrera_id
        INNER JOIN jornada j ON j.cod_jor = l.horario_id
        WHERE 1=1
    ";

        $params = [];

        // Carrera
        if (!empty($carrera)) {
            $in = implode(',', array_fill(0, count($carrera), '?'));
            $sql .= " AND l.carrera_id IN ($in)";
            $params = array_merge($params, $carrera);
        }

        // Horario
        if (!empty($horario)) {
            $in = implode(',', array_fill(0, count($horario), '?'));
            $sql .= " AND l.horario_id IN ($in)";
            $params = array_merge($params, $horario);
        }

        // Número o estado
        if (!empty($numero)) {
            $sql .= " AND c.telefono_principal = ?";
            $params[] = $numero;
        } elseif (!empty($estado)) {
            $in = implode(',', array_fill(0, count($estado), '?'));
            $sql .= " AND l.estado_leads_id IN ($in)";
            $params = array_merge($params, $estado);
        }

        // Asesor
        if (!empty($asesor)) {
            $in = implode(',', array_fill(0, count($asesor), '?'));
            $sql .= " AND l.user_id IN ($in)";
            $params = array_merge($params, $asesor);
        }

        $sql .= " ORDER BY l.id_lead DESC";

        $pdo  = (new Conexion())->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarReporteCRMLeads($asesor = [], $carreras = [], $horario = [], $estados = [])
    {
        $sql = "SELECT 
                IFNULL(p.desc_pro, 'Sin Carrera') AS programa, 
                IFNULL(h.descripcion, 'Sin Horario') AS horario, 
                h.id_horario,
                COUNT(l.id_lead) AS total_leads
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN programa p ON p.cod_pro = l.carrera_id
            LEFT JOIN horario h ON h.id_horario = l.horario_id
            LEFT JOIN user u ON u.id_user = l.user_id
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id
            WHERE l.cod_emp = ?
            AND (
                e.nombre != 'Matriculado'
                OR (e.nombre = 'Matriculado' AND l.foco = ?)
            )
            ";

        $params = [$_SESSION['cod_emp'],$_SESSION['foco']];

        // Seguridad por Rol (Igual que la lista)
        /*if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin' && $texto === "" && empty($asesor)) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }*/

        // Filtro de Texto (Igual que la lista)
        /*if ($texto !== "") {
            $sql .= " AND (c.nombres LIKE ? OR c.apellidos LIKE ? OR c.email LIKE ? OR c.telefono_principal LIKE ?)";
            $buscar = "%$texto%";
            $params = array_merge($params, array_fill(0, 4, $buscar));
        }*/

        // Mismos filtros array que la lista
        $filtros = [
            'l.user_id' => $asesor,
            'p.desc_pro' => $carreras,
            'e.nombre' => $estados
        ];

        foreach ($filtros as $columna => $valores) {
            if (!empty($valores)) {
                $placeholders = implode(",", array_fill(0, count($valores), "?"));
                $sql .= " AND $columna IN ($placeholders)";
                $params = array_merge($params, $valores);
            }
        }

        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND (h.descripcion IN ($placeholders) OR h.id_horario IN ($placeholders))";
            $params = array_merge($params, $horario, $horario);
        }

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        $sql .= " GROUP BY p.desc_pro, h.descripcion, h.id_horario";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function reporteLeadsFuente($texto = "", $asesor = [], $carreras = [], $estados = [], $fecha_inicio = "", $fecha_fin = "")
    {
        $sql = "SELECT

            l.utm_medium AS medio,
            l.utm_source AS fuente,
            l.utm_campaign AS campana,

            SUM(CASE WHEN el.nombre = 'Nuevo Leads' THEN 1 ELSE 0 END) AS nuevo_leads,
            SUM(CASE WHEN el.nombre = 'Prospecto' THEN 1 ELSE 0 END) AS prospecto,
            SUM(CASE WHEN el.nombre = 'Leads Activo' THEN 1 ELSE 0 END) AS leads_activo,
            SUM(CASE WHEN el.nombre = 'Interesado' THEN 1 ELSE 0 END) AS interesado,
            SUM(CASE WHEN el.nombre = 'En Decisión' THEN 1 ELSE 0 END) AS en_decision,
            SUM(CASE WHEN el.nombre = 'Matricula en Proceso' THEN 1 ELSE 0 END) AS matricula_proceso,
            SUM(CASE WHEN el.nombre = 'Matriculado' THEN 1 ELSE 0 END) AS matriculado,
            SUM(CASE WHEN el.nombre = 'Aplazado' THEN 1 ELSE 0 END) AS aplazado,
            SUM(CASE WHEN el.nombre = 'Perdido' THEN 1 ELSE 0 END) AS perdido,

            COUNT(l.id_lead) AS total

        FROM leads l

        INNER JOIN cliente c 
            ON c.id_cliente = l.cliente_id

        LEFT JOIN user u 
            ON u.id_user = l.user_id

        LEFT JOIN estado_leads el 
            ON el.id_estado_leads = l.estado_leads_id

        LEFT JOIN programa p 
            ON p.cod_pro = l.carrera_id

        WHERE l.cod_emp = ?
        AND (
            el.nombre != 'Matriculado'
            OR (el.nombre = 'Matriculado' AND l.foco = ?)
        )
        ";

        $params = [$_SESSION['cod_emp'],$_SESSION['foco']];

        // Seguridad por Rol (Igual que la lista)
        /*if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'Admin' && $texto === "" && empty($asesor)) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }*/

        // Filtro de Texto (Igual que la lista)
        if ($texto !== "") {
            $sql .= " AND (l.utm_source LIKE ? OR l.utm_medium LIKE ? OR l.utm_campaign LIKE ?)";
            $buscar = "%$texto%";
            $params = array_merge($params, array_fill(0, 3, $buscar));
        }

        // Mismos filtros array que la lista
        $filtros = [
            /*'l.user_id' => $asesor,
            'p.desc_pro' => $carreras,*/
            'el.nombre' => $estados
        ];

        foreach ($filtros as $columna => $valores) {
            if (!empty($valores)) {
                $placeholders = implode(",", array_fill(0, count($valores), "?"));
                $sql .= " AND $columna IN ($placeholders)";
                $params = array_merge($params, $valores);
            }
        }

        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND (h.descripcion IN ($placeholders) OR h.id_horario IN ($placeholders))";
            $params = array_merge($params, $horario, $horario);
        }

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        $sql .= "GROUP BY l.utm_medium, l.utm_source, l.utm_campaign ORDER BY total DESC";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ctrReporteEstadoLeads(
        $texto = "",
        $asesor = [],
        $carreras = [],
        $estados = [],
        $fecha_inicio = "",
        $fecha_fin = "",
        $page = 1,
        $limit = 10
    ) {

        $conn = new Conexion();
        $pdo = $conn->conectar();

        // Seguridad básica
        $page  = max(1, (int)$page);
        $limit = max(1, min(100, (int)$limit)); // limite entre 1 y 100
        $offset = ($page - 1) * $limit;

        /* ==============================
        WHERE DINAMICO
        ============================== */

        $where = " WHERE l.emp_log = ? ";
        $params = [$_SESSION['cod_emp']];

        // Filtro texto
        if ($texto !== "") {

            $where .= " AND (c.nombres LIKE ? OR c.apellidos LIKE ?)";
            $buscar = "%$texto%";

            $params[] = $buscar;
            $params[] = $buscar;
        }

        // Filtro asesores
        if (!empty($asesor)) {

            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $where .= " AND l.usu_log IN ($placeholders)";
            $params = array_merge($params, $asesor);
        }

        // Filtro estados
        if (!empty($estados)) {
            // Creamos los placeholders (?, ?, ?)
            $placeholders = implode(",", array_fill(0, count($estados), "?"));

            // Filtramos buscando en las tablas unidas (ea y en)
            $where .= " AND (ea.nombre IN ($placeholders) OR en.nombre IN ($placeholders))";

            // Duplicamos los estados en el array de parámetros porque hay dos "IN"
            foreach ($estados as $est) {
                $params[] = $est;
            }
            foreach ($estados as $est) {
                $params[] = $est;
            }
        }

        // Filtro fechas
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {

            $where .= " AND l.fec_log BETWEEN ? AND ?";

            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        /* ==============================
        CONSULTA PRINCIPAL
        ============================== */

        $sql = "SELECT
                ld.id_lead,
                CONCAT(c.nombres,' ',c.apellidos) AS cliente,
                u.nombres AS asesor,
                IF(en.nombre IS NULL OR en.nombre='',ea.nombre,en.nombre) AS estado_actual,
                COUNT(l.id_log) AS cambios

            FROM leads ld

            INNER JOIN cliente c
            ON c.id_cliente = ld.cliente_id

            LEFT JOIN logleadsestado l
            ON l.idlead_log = ld.id_lead

            LEFT JOIN user u
            ON u.id_user = l.usu_log

            LEFT JOIN estado_leads ea
            ON ea.id_estado_leads = l.eact_log

            LEFT JOIN estado_leads en
            ON en.id_estado_leads = l.enew_log

            $where

            GROUP BY ld.id_lead
            ORDER BY ld.id_lead DESC
            LIMIT $limit OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* ==============================
        TOTAL REGISTROS (SIN DUPLICADOS)
        ============================== */

        $sqlTotal = "SELECT COUNT(DISTINCT ld.id_lead)
             FROM leads ld
             INNER JOIN cliente c ON c.id_cliente = ld.cliente_id
             LEFT JOIN logleadsestado l ON l.idlead_log = ld.id_lead
             LEFT JOIN estado_leads ea ON ea.id_estado_leads = l.eact_log
             LEFT JOIN estado_leads en ON en.id_estado_leads = l.enew_log

                $where";

        $stmtTotal = $pdo->prepare($sqlTotal);
        $stmtTotal->execute($params);

        $total = (int)$stmtTotal->fetchColumn();

        /* ==============================
        RESPUESTA
        ============================== */

        return [
            "data"  => $data,
            "total" => $total,
            "page"  => $page,
            "limit" => $limit
        ];
    }

    public static function ctrReporteEstadoLeadsHistorico($idlead)
    {
        $sql = "SELECT

                u.nombres AS asesor,
                ea.nombre AS estado_anterior,
                en.nombre AS estado_nuevo,
                l.fec_log,
                l.hor_log

            FROM logleadsestado l
            LEFT JOIN user u
            ON u.id_user = l.usu_log
            LEFT JOIN estado_leads ea
            ON ea.id_estado_leads = l.eact_log
            LEFT JOIN estado_leads en
            ON en.id_estado_leads = l.enew_log
            WHERE l.idlead_log = ?

            ORDER BY l.fec_log DESC, l.hor_log ASC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $idlead);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return "error";
    }

    public static function listarReporteRstTEO(
        $texto = "",
        $asesor = []
    ) {
        $codEmp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];
        $sql = "
            SELECT
                r.cod_rst,
                r.fecha,
                r.obs_rst,

                l.id_lead,

                CONCAT(c.nombres, ' ', c.apellidos) AS cliente_nombre,
                c.telefono_principal AS cliente_telefono,

                CONCAT(u.nombres, ' ', u.apellidos) AS asesor_nombre,
                CONCAT(ul.nombres, ' ', ul.apellidos) AS asesor_nombre_lead,
                el.nombre AS estado_leads,
                r.cod_emp,
                tp.des_tipo_trans AS tipo_nom,
                n.desc_not AS nota
            FROM rst_frm r
            LEFT JOIN leads l ON l.id_lead = r.lead_id
            LEFT JOIN cliente c ON c.id_cliente = l.cliente_id
            LEFT JOIN user u ON u.id_user = r.user_id
            LEFT JOIN user ul ON ul.id_user = l.user_id
            LEFT JOIN tipo_trans tp ON tp.id_tipo_trans = r.tipo_trans_id
            LEFT JOIN nota n ON n.id_lead = l.id_lead
            INNER JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id
            WHERE r.cod_emp = ? AND r.user_id = 18
            AND (
                el.nombre != 'Matriculado'
                OR (el.nombre = 'Matriculado' AND l.foco = ?)
            )
                GROUP BY
                r.cod_rst,
                r.fecha,
                r.obs_rst;
        ";

        $params = [$codEmp, $_SESSION['foco']];

        /* ===========================
        VALIDAR SI TODOS LOS FILTROS ESTÁN VACÍOS
        ============================ */
        $todosVacios = (
            $texto === "" &&
            empty($asesor)
        );

        /* ===========================
        FILTRO POR ROL
        ============================ */
        if (isset($_SESSION['rol'])) {
            if ($_SESSION['rol'] !== 'Admin' && $todosVacios) {
                $sql .= " AND r.user_id = ?";
                $params[] = $_SESSION['user_id'];
            }
        }

        /* ===========================
        FILTRO POR TEXTO (cliente / teléfono)
        ============================ */
        if ($texto !== "") {
            $sql .= "
            AND (
                c.nombres LIKE ? OR
                c.apellidos LIKE ? OR
                c.telefono_principal LIKE ?
            )
        ";

            $buscar = "%$texto%";
            array_push($params, $buscar, $buscar, $buscar);
        }

        /* ===========================
        FILTRO POR ASESOR
        ============================ */
        if (!empty($asesor)) {
            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $sql .= " AND l.user_id IN ($placeholders)";
            $params = array_merge($params, $asesor);
        }


        /* ===========================
        ORDEN FINAL
        ============================ */
        $sql .= " ORDER BY l.fecha_creacion DESC";

        /* ===========================
        EJECUCIÓN
        ============================ */
        $conn = new Conexion();
        $pdo = $conn->conectar();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarReporteRstDiaTEO($mes = null, $anio = null)
    {
        // 🔹 Si no vienen mes o año, se calculan automáticamente
        $mes  = $mes  ?? date('m');
        $anio = $anio ?? date('Y');

        $codEmp = $_SESSION['cod_emp'] ?? $_GET['cod_emp'];

        $conn = new Conexion();
        $pdo  = $conn->conectar();

        /* =====================================================
        CONSULTA 1 → LEADS ASIGNADOS POR DÍA Y ASESOR
        ====================================================== */
        $sqlPorDia = "
        SELECT
            DAY(r.fecha) AS dia,
            MONTH(r.fecha) AS mes,
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor,
            CONCAT(ur.nombres, ' ', ur.apellidos) AS asesorRTS,

            COUNT(*) AS total,

            SUM(
                CASE 
                    WHEN tp.id_tipo_trans IS NOT NULL THEN 1
                    ELSE 0
                END
            ) AS tipo,

            tp.des_tipo_trans AS tipo_nom

        FROM rst_frm r
        LEFT JOIN user ur 
            ON ur.id_user = r.user_id
        LEFT JOIN leads l 
            ON r.lead_id = l.id_lead
        LEFT JOIN user u 
            ON u.id_user = l.user_id
        LEFT JOIN tipo_trans tp 
            ON tp.id_tipo_trans = r.tipo_trans_id

        WHERE r.cod_emp = ? AND r.user_id = 18
        AND (
            l.estado_leads_id != '6'
            OR (l.estado_leads_id = '6' AND l.foco = ?)
        )
        

        GROUP BY
            dia,
            asesor,
            asesorRTS

        ORDER BY mes DESC;
        ";
        /*AND MONTH(r.fecha) = ?
        AND YEAR(r.fecha) = ? 
        , $mes, $anio*/
        $stmtDia = $pdo->prepare($sqlPorDia);
        $stmtDia->execute([$codEmp,$_SESSION['foco']]);
        $porDia = $stmtDia->fetchAll(PDO::FETCH_ASSOC);

        /* =====================================================
        CONSULTA 2 → LEADS POR ESTADO Y ASESOR
        ====================================================== */
        $sqlPorEstado = "
        SELECT
            CONCAT(u.nombres, ' ', u.apellidos) AS asesor,
            el.nombre AS estado,
            el.ord_eld AS id,

            COUNT(*) AS total

            FROM rst_frm r
            LEFT JOIN leads l 
                ON r.lead_id = l.id_lead
            LEFT JOIN user u 
                ON u.id_user = l.user_id
            LEFT JOIN estado_leads el 
                ON el.id_estado_leads = l.estado_leads_id

            WHERE r.cod_emp = ? AND r.user_id = 18
            AND (
                el.nombre != 'Matriculado'
                OR (el.nombre = 'Matriculado' AND l.foco = ?)
            )
            GROUP BY
                asesor,
                el.id_estado_leads,
                el.nombre,
                el.ord_eld

            ORDER BY el.ord_eld ASC;

            ";
        /*AND MONTH(r.fecha) = ?
            AND YEAR(r.fecha) = ? 
            , $mes, $anio*/
        $stmtEstado = $pdo->prepare($sqlPorEstado);
        $stmtEstado->execute([$codEmp,$_SESSION['foco']]);
        $porEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);

        /* =====================================================
        RETORNO FINAL (LISTO PARA JS / EXCEL)
        ====================================================== */
        return [
            'mes'       => $mes,
            'anio'      => $anio,
            'porDia'    => $porDia,
            'porEstado' => $porEstado
        ];
    }
}
