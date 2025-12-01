<?php

class LeadsControllers{
    public static function agregarLeads($data, $id_cliente) {
        return LeadsModels::agregarLeads($data, $id_cliente, 0, 1);
    }

    public static function getLeads() {
        return LeadsModels::getLeads();
    }

    public static function updateEstado($idLead, $idEstado) {
        return LeadsModels::updateEstado($idLead, $idEstado);
    }

    public static function listarLeads() {
        return LeadsModels::listarLeads();
    }
}