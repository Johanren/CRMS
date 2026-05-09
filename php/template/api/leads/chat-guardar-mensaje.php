<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

/* =========================================
   FUNCIÓN: DIVIDIR MENSAJES
========================================= */
function dividirMensajes($texto)
{
    preg_match_all(
        '/(Usuario:|Asistente IA:)(.*?)(?=(Usuario:|Asistente IA:|$))/s',
        $texto,
        $matches,
        PREG_SET_ORDER
    );

    $mensajes = [];

    foreach ($matches as $m) {

        $tipo = trim($m[1]);
        $contenido = trim($m[2]);

        /* 🔴 Limpiar ruido del sistema */
        if (
            stripos($contenido, '=CONSULTAAPI=') !== false ||
            stripos($contenido, '=PARAMS_API=') !== false
        ) {
            continue;
        }

        /* Detectar emisor */
        $emisor = ($tipo === 'Usuario:') ? 'cliente' : 'asesor';

        if (!empty($contenido)) {
            $mensajes[] = [
                'emisor' => $emisor,
                'mensaje' => $contenido
            ];
        }
    }

    return $mensajes;
}

try {

    $conn->beginTransaction();

    /* =========================================
       VALIDACIONES
    ========================================= */
    $lead_id = $data['lead_id'] ?? null;
    $mensajeCompleto = trim($data['mensaje'] ?? '');

    if (!$lead_id || !$mensajeCompleto) {
        throw new Exception("Datos incompletos");
    }

    /* =========================================
       VALIDAR LEAD
    ========================================= */
    $sql = "SELECT cliente_id 
            FROM leads 
            WHERE id_lead = ?"; // ⚠️ Ajusta a 'id' si aplica

    $stmt = $conn->prepare($sql);
    $stmt->execute([$lead_id]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lead) {
        throw new Exception("El lead no existe");
    }

    $user_id = $lead['cliente_id'];

    /* =========================================
       BUSCAR O CREAR CONVERSACIÓN
    ========================================= */
    $sql = "SELECT id 
            FROM chat_conversaciones 
            WHERE lead_id = ? 
            AND user_id = ? 
            AND estado = 'activo'
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$lead_id, $user_id]);
    $conv = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$conv) {

        $sql = "INSERT INTO chat_conversaciones 
                (lead_id, user_id, estado) 
                VALUES (?, ?, 'activo')";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$lead_id, $user_id]);

        $conversacion_id = $conn->lastInsertId();

    } else {
        $conversacion_id = $conv['id'];
    }

    /* =========================================
       DIVIDIR MENSAJES
    ========================================= */
    $mensajes = dividirMensajes($mensajeCompleto);

    if (empty($mensajes)) {
        throw new Exception("No se pudieron procesar los mensajes");
    }

    /* =========================================
       INSERTAR MENSAJES
    ========================================= */
    $sql = "INSERT INTO chat_mensajes 
            (conversacion_id, emisor, mensaje)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    foreach ($mensajes as $msg) {
        $stmt->execute([
            $conversacion_id,
            $msg['emisor'],
            $msg['mensaje']
        ]);
    }

    $conn->commit();

    echo json_encode([
        "status" => "ok",
        "conversacion_id" => $conversacion_id,
        "mensajes_insertados" => count($mensajes)
    ]);

} catch (Exception $e) {

    $conn->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}