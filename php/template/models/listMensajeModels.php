<?php

class ListMensajeModels
{
    public static function insertarMensajes($mensajes, $fecha)
    {

        $sql = "INSERT INTO lis_recep 
            (lead_lrec, cel_lrec, nom_lrec, fec_lrec, usr_lrec, mns_lrec)
            VALUES 
            (:id_lead, :numero, :cliente, NOW(), :asesor, :mensaje)";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);

        try {

            $enviados = 0;

            foreach ($mensajes as $key => $m) {

                $stmt->bindParam(':id_lead', $m['id_lead']);
                $stmt->bindParam(':numero', $m['numero']);
                $stmt->bindParam(':cliente', $m['cliente']);
                $stmt->bindParam(':asesor', $m['asesor']);
                $stmt->bindParam(':mensaje', $m['mensaje']);

                $stmt->execute();

                // 2️⃣ Enviar SMS
                $envio = self::enviarSMS(
                    $m['numero'],
                    $m['mensaje'],
                    $m['cliente'],
                    $fecha
                );

                if ($envio) {
                    $enviados++;
                }
            }

            return [
                'ok' => true,
                'enviados' => $enviados
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function listarMensajesRST($id)
    {

        $sql = "SELECT nom_lrec AS cliente, fec_lrec AS fecha, usr_lrec AS asesor, mns_lrec AS mensaje FROM `lis_recep` WHERE lead_lrec = ?";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function enviarSMS($numero, $mensaje, $cliente = '', $dateToSend = null)
    {

        try {

            /*
            ==========================================
            LIMPIAR NÚMERO
            ==========================================
            */
            $numero = preg_replace(
                '/[^0-9]/',
                '',
                $numero
            );

            /*
            ==========================================
            AGREGAR INDICATIVO 57
            ==========================================
            */
            if (strlen($numero) == 10) {

                $numero = '57' . $numero;
            }

            /*
            ==========================================
            VALIDAR NÚMERO
            ==========================================
            */
            if (!preg_match('/^57[0-9]{10}$/', $numero)) {

                return [
                    'ok'    => false,
                    'error' => 'Número inválido'
                ];
            }

            /*
            ==========================================
            PAYLOAD CRWAVE
            ==========================================
            */
            $payload = [

                "messages" => [
                    [
                        "phone_number"   => $numero,
                        "message" => $mensaje
                    ]
                ]

            ];

            /*
            ==========================================
            FECHA PROGRAMADA
            ==========================================
            */
            if (!empty($dateToSend)) {

                $payload['date_to_send'] = date(
                    'Y-m-d H:i:s',
                    strtotime($dateToSend)
                );
            }

            /*
            ==========================================
            JSON
            ==========================================
            */
            $jsonData = json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
            );

            /*
            ==========================================
            CURL
            ==========================================
            */
            $curl = curl_init();

            curl_setopt_array($curl, [

                CURLOPT_URL => 'https://crwave.com.co/client/api/v1/sms/batch/',

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST  => 'POST',

                CURLOPT_POSTFIELDS     => $jsonData,

                CURLOPT_HTTPHEADER => [

                    'Content-Type: application/json',

                    /*
                    ==========================================
                    REEMPLAZA TU TOKEN
                    ==========================================
                    */
                    'Authorization: Token e8248e3af9b51010422e09c55fe7ff517eb12f4fe395d236020652d806639354'

                ],

            ]);

            /*
            ==========================================
            RESPUESTA
            ==========================================
            */
            $response  = curl_exec($curl);
            $error     = curl_error($curl);
            $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            /*
            ==========================================
            ERROR CURL
            ==========================================
            */
            if ($error) {

                return [
                    'ok'    => false,
                    'error' => $error
                ];
            }

            /*
            ==========================================
            DECODIFICAR JSON
            ==========================================
            */
            $data = json_decode($response, true);

            return [

                'ok'        => $httpCode == 202,
                'http_code' => $httpCode,
                'cliente'   => $cliente,
                'numero'    => $numero,
                'response'  => $data,
                'raw'       => $response

            ];
        } catch (Exception $e) {

            return [
                'ok'    => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function reporte1Mensajes()
    {
        $sql = "
        SELECT 
            DATE(lr.fec_lrec) AS fecha,
            -- Si no encuentra coincidencia, lo marca como 'Sin Categoría / Manual'
            IFNULL(tm.tip_mns, 'Manual / Otros') AS tipo_mensaje,
            IFNULL(m.men_mns, 'Mensaje no predefinido') AS plantilla,
            COUNT(*) AS total_enviados
        FROM lis_recep lr
        -- Cambiamos a LEFT JOIN para no perder los que no coinciden
        LEFT JOIN mensaje m ON 
            REPLACE(REPLACE(REPLACE(LOWER(lr.mns_lrec), '\n', ''), '\r', ''), ' ', '') 
            LIKE 
            CONCAT('%', 
                REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    LOWER(m.men_mns), 
                    '{{cliente}}', ''), '{{asesor}}', ''), '{{foco}}', ''), 
                    '{{url}}', ''), '{{carrera}}', ''), '{{jornada}}', ''), 
                    '\n', ''), '\r', ''), ' ', ''), 
            '%')
        LEFT JOIN tipo_mensaje tm ON m.tip_mns = tm.id_tp_mns
        GROUP BY 
            DATE(lr.fec_lrec), 
            tipo_mensaje, 
            plantilla
        ORDER BY 
            fecha DESC, 
            total_enviados DESC;
        ";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_general = array_sum(array_column($data, 'total_enviados'));

        return [
            "status" => "success",
            "data" => $data,
            "total_general" => $total_general // Ahora debería darte 18899
        ];
    }

    public static function reporte2Estados()
    {
        $sql = "
            SELECT 
                DATE(lr.fec_lrec) AS fecha,
                e.nombre,
                COUNT(*) AS total
            FROM lis_recep lr
            JOIN leads l ON lr.lead_lrec = l.id_lead
            INNER JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id
            GROUP BY DATE(lr.fec_lrec), e.nombre
            ORDER BY fecha DESC
            ";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_general = array_sum(array_column($data, 'total'));

        return [
            "status" => "success",
            "data" => $data,
            "total_general" => $total_general
        ];
    }

    public static function reporte3Asesores()
    {
        $sql = "
            SELECT 
                DATE(fec_lrec) AS fecha,
                usr_lrec AS asesor,
                COUNT(*) AS total
            FROM lis_recep
            GROUP BY DATE(fec_lrec), usr_lrec
            ORDER BY fecha DESC
            ";

        $conn = new Conexion();
        $pdo = $conn->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_general = array_sum(array_column($data, 'total'));

        return [
            "status" => "success",
            "data" => $data,
            "total_general" => $total_general
        ];
    }
}
