<?php

class NotificacionesModels
{

    public static function crearNotificacion($data)
    {
        $sql = "INSERT INTO notificaciones 
                (user_id, titulo, mensaje, modulo, referencia)
                VALUES (:user_id, :titulo, :mensaje, :modulo, :referencia)";

        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        return $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':titulo'     => $data['titulo'],
            ':mensaje'    => $data['mensaje'],
            ':modulo'     => $data['modulo'],
            ':referencia' => $data['referencia']
        ]);
    }

    public static function listarTodas()
    {
        $sql = "SELECT 
                n.*,
                l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, 
                p.desc_pro, ci.desc_ciu AS ciudad, l.fecha_creacion, 
                u.nombres AS nombreAsesor, u.apellidos AS apellidoAsesor,
                h.descripcion AS horario, e.nombre AS estado

            FROM notificaciones n

            INNER JOIN leads l 
                ON l.id_lead = JSON_UNQUOTE(
                    JSON_EXTRACT(n.referencia, '$.id')
                )
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN programa p ON p.cod_pro = l.carrera_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN user u ON u.id_user = l.user_id 
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            LEFT JOIN motivo_estado_leads m ON m.id_mot = l.est_motivo
            LEFT JOIN motivo_perdido mt ON mt.id_per = m.per_id
            
            WHERE leida = 0 ORDER BY fecha DESC";
            
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorUsuario($user_id, $limit = null)
    {
        $sql = "SELECT 
                n.*,
                l.*, c.nombres, c.apellidos, c.email, c.telefono_principal, 
                p.desc_pro, ci.desc_ciu AS ciudad, l.fecha_creacion, 
                u.nombres AS nombreAsesor, u.apellidos AS apellidoAsesor,
                h.descripcion AS horario, e.nombre AS estado

            FROM notificaciones n

            INNER JOIN leads l 
                ON l.id_lead = JSON_UNQUOTE(
                    JSON_EXTRACT(n.referencia, '$.id')
                )
            INNER JOIN cliente c ON c.id_cliente = l.cliente_id 
            LEFT JOIN programa p ON p.cod_pro = l.carrera_id 
            LEFT JOIN ciudad ci ON ci.cod_ciu = l.ciudad_id 
            LEFT JOIN user u ON u.id_user = l.user_id 
            LEFT JOIN estado_leads e ON e.id_estado_leads = l.estado_leads_id 
            LEFT JOIN horario h ON l.horario_id = h.id_horario
            LEFT JOIN motivo_estado_leads m ON m.id_mot = l.est_motivo
            LEFT JOIN motivo_perdido mt ON mt.id_per = m.per_id

            WHERE n.user_id = :user_id
            AND n.leida = 0

            ORDER BY n.fecha DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $conn = new Conexion();
        $conectar = $conn->conectar();

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarLeida($id)
    {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id_notificacion = :id";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function contarNoLeidas($user_id = null)
    {
        if ($user_id) {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE leida = 0 AND user_id = :user_id";
            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
        } else {
            $sql = "SELECT COUNT(*) FROM notificaciones WHERE leida = 0";
            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
