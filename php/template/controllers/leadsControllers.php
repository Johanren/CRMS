<?php

class LeadsControllers{
    public static function agregarLeads($data, $id_cliente) {
        return LeadsModels::agregarLeads($data, $id_cliente, $_SESSION['user_id'] ?? 0, 1);
    }
    public static function actualizarLeads($data, $id_cliente) {
        return LeadsModels::actualizarLeads($data, $id_cliente, $_SESSION['user_id'], 1);
    }

    public static function getLeads() {
        return LeadsModels::getLeads();
    }

    public static function updateEstado($idLead, $idEstado) {
        $resp = LeadsModels::updateEstado($idLead, $idEstado);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Medio agregado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarLeads() {
        return LeadsModels::listarLeads();
    }

    public static function listarLeadsId($id) {
        return LeadsModels::listarLeadsId($id);
    }
}