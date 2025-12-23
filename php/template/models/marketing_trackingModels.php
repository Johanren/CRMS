<?php

class Marketing_trackingModels
{
    public static function agregarClick($dato)
    {
        $sql = "INSERT INTO marketing_tracking (utm_source, utm_medium, utm_campaign, ip_usuario) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $dato['sourceField']);
        $stmt->bindParam(2, $dato['mediumField']);
        $stmt->bindParam(3, $dato['campaignField']);
        $stmt->bindParam(4, $dato['ip_usuario']);
        $stmt->execute();
    }

    public static function consultarClickExistete($ip)
    {
        $sql = "SELECT id FROM marketing_tracking WHERE ip_usuario = ? LIMIT 1";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $ip['ip_usuario']);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ? $resultado : false;
    }

    public static function updateClickMarketing($id_marketing, $cliente_id)
    {
        $sql = "UPDATE marketing_tracking SET estado = 'convertido', id_cliente_crm = ? WHERE id = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $cliente_id);
        $stmt->bindParam(2, $id_marketing);
        $stmt->execute();
    }

    public static function utm_campaignClic()
    {
        $sql = "SELECT 
        utm_campaign,
        SUM(CASE WHEN estado = 'visita' THEN 1 ELSE 0 END)     AS clicks,
        SUM(CASE WHEN estado = 'convertido' THEN 1 ELSE 0 END) AS convertidos
        FROM marketing_tracking
        WHERE utm_campaign IS NOT NULL
        AND utm_campaign <> ''
        GROUP BY utm_campaign
        ORDER BY clicks DESC";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
