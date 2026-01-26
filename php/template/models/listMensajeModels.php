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
                $envio = ListMensajeModels::enviarSMS(
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

    private static function enviarSMS($numero, $mensaje, $cliente, $dateToSend = null)
    {
        /* =========================
       VARIABLES DINÁMICAS
    ========================= */
        $country        = "57";
        $encoding       = "string";
        $messageFormat  = 1;

        /* =========================
       LISTA DESTINATARIOS
    ========================= */
        $addresseeList = [
            [
                "mobile"           => $numero,
                "correlationLabel" => $cliente
            ]
        ];

        /* =========================
       DATA BASE
    ========================= */
        $postData = [
            "country"        => $country,
            "message"        => $mensaje,
            "encoding"       => $encoding,
            "messageFormat"  => $messageFormat,
            "addresseeList"  => $addresseeList
        ];

        /* =========================
       ENVÍO PROGRAMADO (OPCIONAL)
       Formato requerido:
       yyyy-MM-dd HH:mm:ss
    ========================= */
        if (!empty($dateToSend)) {
            $postData["dateToSend"] = date('Y-m-d H:i:s', strtotime($dateToSend));
        }

        $jsonData = json_encode($postData, JSON_UNESCAPED_UNICODE);

        /* =========================
       CURL
    ========================= */
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://apitellit.aldeamo.com/SmsiWS/smsSendPost/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Basic bXVsdGljb21wdXRvOk5Ca2xmZCRzYXM1ZGM='
            ],
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return [
                'ok' => false,
                'error' => $error
            ];
        }

        return [
            'ok'       => true,
            'response' => $response
        ];
    }
}
