<?php

class FiltroModels
{
    public static function agregarFiltro($id_user, $nombre, $filtro)
    {
        $sql = "INSERT INTO filtros_usuarios (usuario_id, nombre, filtros)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE filtros = ?, fecha_actualizacion = NOW()";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        $stmt->bindParam(2, $nombre);
        $stmt->bindParam(3, $filtro);
        $stmt->bindParam(4, $filtro);
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Filtros guardados'];
        }

        return "error";
    }

    public static function eliminarFiltro($id_user)
    {
        $sql = "DELETE FROM filtros_usuarios WHERE usuario_id = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $id_user);
        if ($stmt->execute()) {
            return [
                "success" => true,
                "message" => "Filtros eliminados correctamente"
            ];
        }

        return [
            "success" => false,
            "message" => "Error al eliminar filtros"
        ];
    }

    public static function cargarFiltro($id_user, $nombre)
    {
        try {
            $emp  = $_SESSION['cod_emp'] ?? null;
            $foco = $_SESSION['foco'] ?? null;

            $sql = "SELECT filtros 
                FROM filtros_usuarios 
                WHERE usuario_id = ? AND nombre = ? 
                LIMIT 1";

            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);

            $stmt->execute([$id_user, $nombre]);
            $filtros = $stmt->fetchColumn();

            $data = [
                "texto" => "",
                "asesor" => [],
                "carreras" => [],
                "horario" => [],
                "interes" => [],
                "medio" => [],
                "fuente" => [],
                "campana" => [],
                "accion" => [],
                "departamento" => [],
                "ciudad" => [],
                "barrio" => [],
                "estados" => [
                    "Nuevo Leads",
                    "Prospecto",
                    "Leads Activo",
                    "Interesado",
                    "En Decisión"
                ],
                "fecha_inicio" => "",
                "fecha_fin" => ""
            ];

            if ($filtros) {

                $decoded = json_decode($filtros, true);

                // 🔴 doble JSON
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }

                if (is_array($decoded)) {
                    $data = array_merge($data, $decoded);
                }
            }

            if ($emp == 2 && !empty($foco)) {

                $sqlCarreras = "SELECT DISTINCT p.desc_pro
                            FROM programa p
                            INNER JOIN foco_detalle fc ON fc.prog_fde = p.cod_pro
                            WHERE p.emp_pro = 2
                            AND fc.foc_fde = ?
                            ORDER BY p.desc_pro ASC";

                $stmt2 = $conectar->prepare($sqlCarreras);
                $stmt2->execute([$foco]);

                $carrerasValidas = $stmt2->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($data['carreras'])) {
                    // 🔹 filtrar si ya tenía
                    $data['carreras'] = array_values(
                        array_intersect($data['carreras'], $carrerasValidas)
                    );
                } else {
                    // 🔹 si no tenía → llenar con foco
                    $data['carreras'] = $carrerasValidas;
                }
            }

            return json_encode($data);
        } catch (Exception $e) {
            return json_encode(null);
        }
    }
}
