<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

if (!isset($data['lead_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lead ID es obligatorio'
    ]);
    exit;
}

$lead_id   = $data['lead_id'];
$asesor_id = $data['user_id']  ?? null;
$cod_emp   = $data['cod_emp']  ?? null;

$setCliente = [];
$setLead = [];
$params = [];

function normalizarTelefono($telefono)
{
    $telefono = preg_replace('/\D/', '', $telefono);
    return (substr($telefono, 0, 2) === '57')
        ? substr($telefono, 2)
        : $telefono;
}

/* ====== CAMPOS CLIENTE ====== */

$camposCliente = [
    'email' => 'c.email',
    'telefono_principal' => 'c.telefono_principal',
    'identificacion' => 'c.identificacion',
    'nombres' => 'c.nombres',
    'apellidos' => 'c.apellidos'
];

foreach ($camposCliente as $jsonKey => $dbField) {
    if (isset($data[$jsonKey]) && $data[$jsonKey] !== '') {

        $valor = $data[$jsonKey];

        if ($jsonKey === 'telefono_principal') {
            $valor = normalizarTelefono($valor);
        }

        $setCliente[] = "$dbField = ?";
        $params[] = $valor;
    }
}

/* ====== CAMPOS LEAD ====== */

$camposLead = [
    'user_id'     => 'l.user_id',
    'estado_lead' => 'l.estado_leads_id',
    'horario_id'  => 'l.horario_id',
    'carrera_id'  => 'l.carrera_id',
    'utm_source'  => 'l.utm_source',
    'utm_medium'  => 'l.utm_medium',
    'utm_campaign' => 'l.utm_campaign'
];

foreach ($camposLead as $jsonKey => $dbField) {
    if (isset($data[$jsonKey]) && $data[$jsonKey] !== '') {
        $setLead[] = "$dbField = ?";
        $params[] = $data[$jsonKey];
    }
}

/* ====== VALIDAR SI HAY ALGO PARA ACTUALIZAR ====== */

if (empty($setCliente) && empty($setLead) && empty($data['tit_not']) && empty($data['desc_not'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No hay datos para actualizar'
    ]);
    exit;
}

/* ====== USAMOS TRANSACCIÓN ====== */

$conn->beginTransaction();

try {

    /* ====== UPDATE CLIENTE + LEAD ====== */
    if (!empty($setCliente) || !empty($setLead)) {

        $setTotal = array_merge($setCliente, $setLead);
        $setString = implode(', ', $setTotal);

        $sql = "UPDATE cliente c
                INNER JOIN leads l ON l.cliente_id = c.id_cliente
                SET $setString
                WHERE l.id_lead = ?";

        $params[] = $lead_id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
    }

    /* ====== INSERTAR NOTA ====== */

    if (!empty($data['desc_not'])) {

        if (!$asesor_id || !$cod_emp) {
            throw new Exception("Faltan datos de asesor o empresa para registrar la nota");
        }

        $sqlNota = "INSERT INTO nota 
                    (desc_not, id_lead, user_id, cod_emp)
                    VALUES (?,?,?,?)";

        $stmtNota = $conn->prepare($sqlNota);
        $stmtNota->execute([
            $data['desc_not'],
            $lead_id,
            18,//$asesor_id,
            $cod_emp
        ]);
    }

    /* Crear RST */
    $sql = "INSERT INTO rst_frm
            (lead_id, obs_rst, tipo_trans_id, user_id, cod_emp, bd_rst)
            VALUES (?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    /*$stmt->execute([
        $lead_id,
        'Lead Actualizado Por TEO',
        2,
        18,//$asesor_id,
        $cod_emp,
        1
    ]);*/

    $conn->commit();

    echo json_encode([
        'status' => 'ok',
        'message' => 'Cliente actualizado correctamente'
    ]);
} catch (Exception $e) {

    $conn->rollBack();

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
