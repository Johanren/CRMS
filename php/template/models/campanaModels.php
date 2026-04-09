<?php

class CampanaModels
{
    public static function agregarCampana($data)
    {
        $sql = "INSERT INTO campana (nombre, codigo, fecha, id_audiencia) VALUES (?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nombre"]);
        $stmt->bindParam(2, $data["codigo"]);
        $stmt->bindParam(3, $data["fecha"]);
        $stmt->bindParam(4, $data["audiencia"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function agregarCampanasxmedio($data)
    {
        $sql = "INSERT INTO campana_x_medio_fuente (cam_cxm, med_cxm, fue_cxm, fec_cxm, rsc_cxm) VALUES (?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["cam_cxm"]);
        $stmt->bindParam(2, $data["med_cxm"]);
        $stmt->bindParam(3, $data["fue_cxm"]);
        $stmt->bindParam(4, $data["fec_cxm"]);
        $stmt->bindParam(5, $data["rsc_cxm"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function agregarCampanas($data, $foto)
    {
        /* ================= IMAGEN ================= */
        $rutaFoto = null;

        if (!empty($foto['img_cam']['tmp_name'])) {

            $carpeta = "uploads/campanas/";

            // Crear carpeta si no existe
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            // Obtener extensión
            $extension = pathinfo($foto['img_cam']['name'], PATHINFO_EXTENSION);

            // Nombre único
            $nombreFoto = "user_" . $data['nom_cam'] . "_" . time() . "." . $extension;

            $rutaFoto = $carpeta . $nombreFoto;

            // Mover imagen
            if (!move_uploaded_file($foto['img_cam']['tmp_name'], $rutaFoto)) {
                return "error_imagen";
            }
        }

        $sql = "INSERT INTO campanas (nom_cam, fre_cam, fini_cam, ffin_cam, det_cam, act_cam, img_cam, emp_cam, foc_cam) VALUES (?,?,?,?,?,?,?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_cam"]);
        $stmt->bindParam(2, $data["fre_cam"]);
        $stmt->bindParam(3, $data["fini_cam"]);
        $stmt->bindParam(4, $data["ffin_cam"]);
        $stmt->bindParam(5, $data["det_cam"]);
        $stmt->bindParam(6, $data["act_cam"]);
        $stmt->bindParam(7, $rutaFoto);
        $stmt->bindParam(8, $_SESSION['cod_emp']);
        $stmt->bindParam(9, $_SESSION['foco']);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function actuaizarCampanas($data, $foto)
    {
        /* ================= IMAGEN ================= */
        $rutaFoto = $data['img_cam_edit']; // por defecto conserva la actual

        if (!empty($foto['img_cam']['tmp_name'])) {
            $carpeta = "uploads/campanas/";

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            $extension = pathinfo($foto['img_cam']['name'], PATHINFO_EXTENSION);
            $nombreFoto = "user_" . $data['nom_cam'] . "_" . time() . "." . $extension;
            $rutaNueva = $carpeta . $nombreFoto;

            if (move_uploaded_file($foto['img_cam']['tmp_name'], $rutaNueva)) {

                // Eliminar imagen anterior si existe
                if (!empty($foto['img_cam_edit']) && file_exists($foto['img_cam_edit'])) {
                    unlink($data['img_cam_edit']);
                }

                $rutaFoto = $rutaNueva;
            }
        }

        $sql = "UPDATE campanas SET nom_cam=?,fre_cam=?,fini_cam=?,ffin_cam=?,det_cam=?,act_cam=?,img_cam=?,emp_cam=?,foc_cam=? WHERE cod_cam=?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["nom_cam"]);
        $stmt->bindParam(2, $data["fre_cam"]);
        $stmt->bindParam(3, $data["fini_cam"]);
        $stmt->bindParam(4, $data["ffin_cam"]);
        $stmt->bindParam(5, $data["det_cam"]);
        $stmt->bindParam(6, $data["act_cam"]);
        $stmt->bindParam(7, $rutaFoto);
        $stmt->bindParam(8, $_SESSION['cod_emp']);
        $stmt->bindParam(9, $_SESSION['foco']);
        $stmt->bindParam(10, $data["campana_id"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }
    
    public static function actuaizarCampanasxmedio($data)
    {

        $sql = "UPDATE campana_x_medio_fuente SET cam_cxm=?,med_cxm=?,fue_cxm=?,fec_cxm=?,rsc_cxm=? WHERE rsc_cxm=?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->bindParam(1, $data["cam_cxm"]);
        $stmt->bindParam(2, $data["med_cxm"]);
        $stmt->bindParam(3, $data["fue_cxm"]);
        $stmt->bindParam(4, $data["fec_cxm"]);
        $stmt->bindParam(5, $data["rsc_cxm"]);
        $stmt->bindParam(6, $data["campanaxmedio_id"]);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    public static function listarCampana()
    {
        $sql = "SELECT * FROM campana c INNER JOIN tipo_audiencia t ON c.id_audiencia = t.id_audiencia";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanas()
    {
        $sql = "SELECT * FROM campanas";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanasxmedio()
    {
        $sql = "SELECT * FROM campana_x_medio_fuente cxm INNER JOIN campanas c ON c.cod_cam = cxm.cam_cxm INNER JOIN medio1 mo ON mo.cod_med = cxm.med_cxm INNER JOIN fuente1 ft ON ft.cod_fue = cxm.fue_cxm";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanaId($id)
    {
        $sql = "SELECT * FROM campana c INNER JOIN tipo_audiencia t ON c.id_audiencia = t.id_audiencia WHERE id_campana = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanasId($id)
    {
        $sql = "SELECT * FROM campanas WHERE cod_cam = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarCampanasxmedioId($id)
    {
        $sql = "SELECT * FROM campana_x_medio_fuente WHERE rsc_cxm = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCampana($id)
    {
        $sql = "DELETE FROM campana WHERE id_campana = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCampanas($id)
    {
        $sql = "DELETE FROM campanas WHERE cod_cam = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function eliminarCampanasxmedio($id)
    {
        $sql = "DELETE FROM campana_x_medio_fuente WHERE rsc_cxm = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
