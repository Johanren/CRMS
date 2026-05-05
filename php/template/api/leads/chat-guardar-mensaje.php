<?php
foreach (glob("../../models/*.php") as $filename) {
    require_once $filename;
}

$data = json_decode(file_get_contents("php://input"), true);
$conn = (new Conexion())->conectar();

/* 🔹 Detectar emisor automáticamente */
function detectarEmisor(&$mensaje)
{
    if (stripos($mensaje, 'Usuario:') === 0) {
        $mensaje = trim(substr($mensaje, strlen('Usuario:')));
        return 'cliente';
    }

    if (stripos($mensaje, 'Asistente IA:') === 0) {
        $mensaje = trim(substr($mensaje, strlen('Asistente IA:')));
        return 'asesor';
    }

    // fallback
    return 'asesor';
}

try {

    $conn->beginTransaction();

    /* 🔹 Validaciones básicas */
    $lead_id = $data['lead_id'] ?? null;
    $mensaje = trim($data['mensaje'] ?? '');

    if (!$lead_id || !$mensaje) {
        throw new Exception("Datos incompletos");
    }

    /* 🔹 Detectar tipo automáticamente */
    $emisor = detectarEmisor($mensaje);

    /* 🔹 Validar que el lead exista */
    $sql = "SELECT cliente_id FROM leads WHERE id_lead = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$lead_id]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lead) {
        throw new Exception("El lead no existe");
    }

    $user_id = $lead['cliente_id'];

    /* 🔹 Buscar conversación activa */
    $sql = "SELECT id 
            FROM chat_conversaciones 
            WHERE lead_id = ? AND user_id = ? AND estado = 'activo'
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$lead_id, $user_id]);
    $conv = $stmt->fetch(PDO::FETCH_ASSOC);

    /* 🔹 Si no existe → crear */
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

    /* 🔹 Insertar mensaje */
    $sql = "INSERT INTO chat_mensajes 
            (conversacion_id, emisor, mensaje)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $conversacion_id,
        $emisor,
        $mensaje
    ]);

    $conn->commit();

    echo json_encode([
        "status" => "ok",
        "conversacion_id" => $conversacion_id,
        "emisor_detectado" => $emisor
    ]);

} catch (Exception $e) {

    $conn->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}