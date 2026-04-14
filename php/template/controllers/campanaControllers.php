<?php

class CampanaControllers
{
    public static function agregarCampana($dato)
    {
        $resp = CampanaModels::agregarCampana($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function agregarCampanas($dato, $foto)
    {
        $resp = CampanaModels::agregarCampanas($dato, $foto);

        if (is_numeric($resp)) {

            $idCampana = $resp;

            if (!empty($dato["med_cxm"])) {

                $dato["id_campana"] = $idCampana;
                $respMedios = CampanaModels::agregarCampanasxmedio($dato);

                if ($respMedios != "ok") {
                    return ["status" => "error", "message" => "Campaña creada pero falló medios"];
                }
            }

            return ["status" => "success", "message" => "Campaña agregada correctamente"];
        }

        return ["status" => "error", "message" => "No se pudo registrar"];
    }

    public static function agregarCampanasxmedio($dato)
    {
        $resp = CampanaModels::agregarCampanasxmedio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function actuaizarCampanas($dato, $foto)
    {
        $resp = CampanaModels::actuaizarCampanas($dato, $foto);

        if ($resp == "ok") {

            // 🔹 Validar si vienen medios
            if (!empty($dato["med_cxm"])) {

                $dato["id_campana"] = $dato["campana_id"];

                // 🔥 OPCIÓN 1 (RECOMENDADA): limpiar e insertar todo de nuevo
                CampanaModels::eliminarCampanasxmedio($dato["campana_id"]);

                $respMedios = CampanaModels::agregarCampanasxmedio($dato);

                if ($respMedios != "ok") {
                    return [
                        "status" => "error",
                        "message" => "Campaña actualizada pero falló medios"
                    ];
                }
            }

            return [
                "status" => "success",
                "message" => "Campaña actualizada correctamente"
            ];
        }

        return [
            "status" => "error",
            "message" => "No se pudo actualizar"
        ];
    }

    public static function actuaizarCampanasxmedio($dato)
    {
        $resp = CampanaModels::actuaizarCampanasxmedio($dato);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio actualizada correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarCampana()
    {
        return CampanaModels::listarCampana();
    }

    public static function listarCampanas()
    {
        return CampanaModels::listarCampanas();
    }

    public static function listarCampanasPagi($page, $limit)
    {
        return CampanaModels::listarCampanasPagi($page, $limit);
    }

    public static function listarCampanasxmedio($id)
    {
        return CampanaModels::listarCampanasxmedio($id);
    }

    public static function listarCampanaId($id)
    {
        return CampanaModels::listarCampanaId($id);
    }

    public static function listarCampanasId($id)
    {
        return CampanaModels::listarCampanasId($id);
    }

    public static function listarCampanasxmedioId($id)
    {
        return CampanaModels::listarCampanasxmedioId($id);
    }

    public static function eliminarCampana($id)
    {
        $resp = CampanaModels::eliminarCampana($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }

    public static function eliminarCampanas($id)
    {
        $resp = CampanaModels::eliminarCampana($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }

    public static function eliminarCampanasxmedio($id)
    {
        $resp = CampanaModels::eliminarCampanasxmedio($id);

        if ($resp == "ok") {
            return ["status" => "success", "message" => "Campaña x medio eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo eliminado"];
        }
    }
}
