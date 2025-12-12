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
            if (!empty($data['tit_not']) || !empty($data['desc_not']) || !empty($data['desc_arch'])) {
                $ultimoId = $conectar->lastInsertId();
                return $ultimoId;
            }
            return "ok";
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

    public static function getLeads($texto = "", $asesor = [], $carreras = [], $horario = [], $interes = [], $medio = [], $fuente = [], $campana = [], $accion = [], $departamento = [], $ciudad = [], $barrio = [], $estados = [], $fecha_inicio = [], $fecha_fin = [])
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
                e.nombre,
                u.nombres AS nombreAsesor
            FROM leads l
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN programa p ON l.carrera_id = p.cod_pro 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            LEFT JOIN user u ON l.user_id = u.id_user
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            WHERE l.cod_emp = ?";

        $params = [$_SESSION['cod_emp']];

        /* ===========================
        FILTRO POR ROL (solo si NO hay filtros)
        ============================ */
        $todosVacios = (
            $texto === ""
        );

        /* ===========================
        FILTRO POR ROL
        ============================ */
        if ($_SESSION['rol'] !== 'Admin'  && $todosVacios) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }

        /* ===========================
            FILTRO POR TEXTO
        ============================ */
        if ($texto !== "") {
            $sql .= " AND (
            c.nombres LIKE ? OR 
            c.apellidos LIKE ? OR 
            c.email LIKE ? OR 
            c.telefono_principal LIKE ? OR
            l.fecha_creacion LIKE ?
        )";

            $buscar = "%$texto%";
            $params = array_merge($params, [$buscar, $buscar, $buscar, $buscar, $buscar]);
        }

        /* ===========================
            FILTRO POR Asesor
        ============================ */
        if (!empty($asesor)) {
            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $sql .= " AND l.user_id IN ($placeholders)";
            $params = array_merge($params, $asesor);
        }

        /* ===========================
            FILTRO POR CARRERAS
        ============================ */
        if (!empty($carreras)) {
            $placeholders = implode(",", array_fill(0, count($carreras), "?"));
            $sql .= " AND p.desc_pro IN ($placeholders)";
            $params = array_merge($params, $carreras);
        }

        /* ===========================
            FILTRO POR Horario
        ============================ */
        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND l.horario_id IN ($placeholders)";
            $params = array_merge($params, $horario);
        }

        /* ===========================
            FILTRO POR Interes
        ============================ */
        if (!empty($interes)) {
            $placeholders = implode(",", array_fill(0, count($interes), "?"));
            $sql .= " AND l.interes_id IN ($placeholders)";
            $params = array_merge($params, $interes);
        }

        /* ===========================
            FILTRO POR medio
        ============================ */
        if (!empty($medio)) {
            $placeholders = implode(",", array_fill(0, count($medio), "?"));
            $sql .= " AND l.medio_id IN ($placeholders)";
            $params = array_merge($params, $medio);
        }

        /* ===========================
            FILTRO POR fuente
        ============================ */
        if (!empty($fuente)) {
            $placeholders = implode(",", array_fill(0, count($fuente), "?"));
            $sql .= " AND l.fuente_id IN ($placeholders)";
            $params = array_merge($params, $fuente);
        }

        /* ===========================
            FILTRO POR campana
        ============================ */
        if (!empty($campana)) {
            $placeholders = implode(",", array_fill(0, count($campana), "?"));
            $sql .= " AND l.campana_id IN ($placeholders)";
            $params = array_merge($params, $campana);
        }

        /* ===========================
            FILTRO POR Accion
        ============================ */
        if (!empty($accion)) {
            $placeholders = implode(",", array_fill(0, count($accion), "?"));
            $sql .= " AND l.accion_id IN ($placeholders)";
            $params = array_merge($params, $accion);
        }

        /* ===========================
            FILTRO POR departamento
        ============================ */
        if (!empty($departamento)) {
            $placeholders = implode(",", array_fill(0, count($departamento), "?"));
            $sql .= " AND l.departamento_id IN ($placeholders)";
            $params = array_merge($params, $departamento);
        }

        /* ===========================
            FILTRO POR ciudad
        ============================ */
        if (!empty($ciudad)) {
            $placeholders = implode(",", array_fill(0, count($ciudad), "?"));
            $sql .= " AND l.ciudad_id IN ($placeholders)";
            $params = array_merge($params, $ciudad);
        }

        /* ===========================
            FILTRO POR barrio
        ============================ */
        if (!empty($barrio)) {
            $placeholders = implode(",", array_fill(0, count($barrio), "?"));
            $sql .= " AND l.barrio_id IN ($placeholders)";
            $params = array_merge($params, $barrio);
        }

        /* ===========================
            FILTRO POR ESTADOS
        ============================ */
        if (!empty($estados)) {

            $placeholders = implode(",", array_fill(0, count($estados), "?"));
            $sql .= " AND e.nombre IN ($placeholders)";
            $params = array_merge($params, $estados);
        }

        /* ===========================
            FILTRO POR FECHA
        ============================ */
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        /* ===========================
            ORDENAR POR FECHA DESCENDENTE
        ============================ */
        $sql .= " ORDER BY l.fecha_creacion DESC";

        /* ===========================
            EJECUCIÓN
        ============================ */
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

    public static function listarLeads($texto = "", $asesor = [], $carreras = [], $horario = [], $interes = [], $medio = [], $fuente = [], $campana = [], $accion = [], $departamento = [], $ciudad = [], $barrio = [], $estados = [], $fecha_inicio = [], $fecha_fin = [])
    {
        $sql = "SELECT 
                l.*, 
                c.nombres, 
                c.apellidos, 
                c.email, 
                c.telefono_principal, 
                p.desc_pro, 
                ci.desc_ciu AS ciudad, 
                l.fecha_creacion, 
                u.nombres AS nombreAsesor, 
                u.apellidos AS apellidoAsesor,
                h.descripcion AS horario,
                e.nombre AS estado
            FROM leads l 
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN programa p ON p.cod_pro = l.carrera_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN user u ON u.id_user = l.user_id 
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            WHERE l.cod_emp = ?";

        $params = [$_SESSION['cod_emp']];

        /* ===========================
        FILTRO POR ROL (solo si NO hay filtros)
        ============================ */
        $todosVacios = (
            $texto === ""
        );

        /* ===========================
        FILTRO POR ROL
        ============================ */
        if ($_SESSION['rol'] !== 'Admin' && $todosVacios) {
            $sql .= " AND l.user_id = ?";
            $params[] = $_SESSION['user_id'];
        }

        /* ===========================
            FILTRO POR TEXTO
        ============================ */
        if ($texto !== "") {
            $sql .= " AND (
            c.nombres LIKE ? OR 
            c.apellidos LIKE ? OR 
            c.email LIKE ? OR 
            c.telefono_principal LIKE ? OR
            l.fecha_creacion LIKE ?
        )";

            $buscar = "%$texto%";
            $params = array_merge($params, [$buscar, $buscar, $buscar, $buscar, $buscar]);
        }

        /* ===========================
            FILTRO POR Asesor
        ============================ */
        if (!empty($asesor)) {
            $placeholders = implode(",", array_fill(0, count($asesor), "?"));
            $sql .= " AND l.user_id IN ($placeholders)";
            $params = array_merge($params, $asesor);
        }

        /* ===========================
            FILTRO POR CARRERAS
        ============================ */
        if (!empty($carreras)) {
            $placeholders = implode(",", array_fill(0, count($carreras), "?"));
            $sql .= " AND p.desc_pro IN ($placeholders)";
            $params = array_merge($params, $carreras);
        }

        /* ===========================
            FILTRO POR Horario
        ============================ */
        if (!empty($horario)) {
            $placeholders = implode(",", array_fill(0, count($horario), "?"));
            $sql .= " AND l.horario_id IN ($placeholders)";
            $params = array_merge($params, $horario);
        }

        /* ===========================
            FILTRO POR Interes
        ============================ */
        if (!empty($interes)) {
            $placeholders = implode(",", array_fill(0, count($interes), "?"));
            $sql .= " AND l.interes_id IN ($placeholders)";
            $params = array_merge($params, $interes);
        }

        /* ===========================
            FILTRO POR medio
        ============================ */
        if (!empty($medio)) {
            $placeholders = implode(",", array_fill(0, count($medio), "?"));
            $sql .= " AND l.medio_id IN ($placeholders)";
            $params = array_merge($params, $medio);
        }

        /* ===========================
            FILTRO POR fuente
        ============================ */
        if (!empty($fuente)) {
            $placeholders = implode(",", array_fill(0, count($fuente), "?"));
            $sql .= " AND l.fuente_id IN ($placeholders)";
            $params = array_merge($params, $fuente);
        }

        /* ===========================
            FILTRO POR campana
        ============================ */
        if (!empty($campana)) {
            $placeholders = implode(",", array_fill(0, count($campana), "?"));
            $sql .= " AND l.campana_id IN ($placeholders)";
            $params = array_merge($params, $campana);
        }

        /* ===========================
            FILTRO POR Accion
        ============================ */
        if (!empty($accion)) {
            $placeholders = implode(",", array_fill(0, count($accion), "?"));
            $sql .= " AND l.accion_id IN ($placeholders)";
            $params = array_merge($params, $accion);
        }

        /* ===========================
            FILTRO POR departamento
        ============================ */
        if (!empty($departamento)) {
            $placeholders = implode(",", array_fill(0, count($departamento), "?"));
            $sql .= " AND l.departamento_id IN ($placeholders)";
            $params = array_merge($params, $departamento);
        }

        /* ===========================
            FILTRO POR ciudad
        ============================ */
        if (!empty($ciudad)) {
            $placeholders = implode(",", array_fill(0, count($ciudad), "?"));
            $sql .= " AND l.ciudad_id IN ($placeholders)";
            $params = array_merge($params, $ciudad);
        }

        /* ===========================
            FILTRO POR barrio
        ============================ */
        if (!empty($barrio)) {
            $placeholders = implode(",", array_fill(0, count($barrio), "?"));
            $sql .= " AND l.barrio_id IN ($placeholders)";
            $params = array_merge($params, $barrio);
        }

        /* ===========================
            FILTRO POR ESTADOS
        ============================ */
        if (!empty($estados)) {
            $placeholders = implode(",", array_fill(0, count($estados), "?"));
            $sql .= " AND e.nombre IN ($placeholders)";
            $params = array_merge($params, $estados);
        }

        /* ===========================
            FILTRO POR FECHA
        ============================ */
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {

            $sql .= " AND DATE(l.fecha_creacion) BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        /* ====================
        =======
            ORDENAR POR FECHA DESCENDENTE
        ============================ */
        $sql .= " ORDER BY l.fecha_creacion DESC";

        /* ===========================
            EJECUCIÓN
        ============================ */
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarLeadsId($id)
    {
        $sql = "SELECT l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, ci.desc_ciu AS ciudad, l.fecha_creacion, f.desc_fue, c.direccion, c.identificacion, el.nombre AS estado_leads, es.des_seguimiento, pro.desc_pro, pro.val_pro, em.nom_emp, h.descripcion AS horario, i.nombre AS interes, m.desc_med, cam.nombre AS campana, acc.nombre AS accion, dep.desc_dep, brr.desc_brr, l.observaciones, u.nombres AS nombreAsesor, u.apellidos AS apellidoAsesor FROM leads l LEFT JOIN cliente c ON c.id_cliente = l.cliente_id LEFT JOIN horario h ON h.id_horario = l.horario_id LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id LEFT JOIN user u ON u.id_user = l.user_id LEFT JOIN fuente1 f ON f.cod_fue = l.fuente_id LEFT JOIN estado_leads el ON el.id_estado_leads = l.estado_leads_id LEFT JOIN estado_seguimiento es ON es.id_seguimiento = l.estadoLeadsSeguimiento LEFT JOIN programa pro ON pro.cod_pro = l.carrera_id LEFT JOIN empresa em ON em.id_emp = pro.emp_pro LEFT JOIN interes i ON i.id_interes = l.interes_id LEFT JOIN medio1 m ON m.cod_med = l.medio_id LEFT JOIN campana cam ON cam.id_campana = l.campana_id LEFT JOIN accion acc ON acc.id_accion = l.accion_id LEFT JOIN departamento dep ON dep.cod_dep = l.departamento_id LEFT JOIN barrio brr ON brr.id_barrio = l.barrio_id WHERE l.id_lead = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerAsesorConMenosLeads($data)
    {
        $cod_emp = $data['cod_emp'] ?? $_SESSION['cod_emp'] ?? null;

        $sql = "SELECT l.user_id, COUNT(*) AS total FROM leads l INNER JOIN user u ON u.id_user = l.user_id INNER JOIN user_role ur ON ur.id_rol = u.rol_id WHERE l.cod_emp = '$cod_emp' AND ur.activo = 1 GROUP BY l.user_id ORDER BY total ASC LIMIT 1";

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si NO hay leads aún, tomar cualquier usuario asesor
        if (!$res) {
            $sql2 = "SELECT u.id_user AS user_id 
                 FROM user u
                 INNER JOIN user_role r ON u.rol_id = r.id_rol
                 WHERE r.nombre_rol like '%asesor%' AND r.activo = 1
                 ORDER BY u.id_user ASC
                 LIMIT 1";
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
}
