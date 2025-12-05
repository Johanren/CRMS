<?php

class ClienteControllers
{
    public static function listarCliente()
    {
        return ClienteModels::listarCliente();
    }

    public static function agregarCliente($dato)
    {
        $resp = ClienteModels::agregarCliente($dato);
        if ($resp > 0) {
            $id_cliente = $resp;
            //Agregar el leads
            $resp_leads = LeadsControllers::agregarLeads($dato, $id_cliente);
            if ($resp_leads == "ok") {
                return ["status" => "success", "message" => "Leads agregado correctamente"];
            } else {
                return ["status" => "error", "message" => "No se pudo registrar"];
            }
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function actualizarCliente($dato)
    {
        $resp = ClienteModels::actualizarCliente($dato);
        if ($resp == "ok") {
            //Actualizar el leads
            $resp_leads = LeadsControllers::actualizarLeads($dato, $dato['cliente_id']);
            if ($resp_leads == "ok") {
                return ["status" => "success", "message" => "Leads actualizado correctamente"];
            } else {
                return ["status" => "error", "message" => "No se pudo registrar"];
            }
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }

    public static function listarClienteId($id)
    {
        return ClienteModels::listarClienteId($id);
    }

    public static function eliminarCliente($id)
    {
        $resp = ClienteModels::eliminarCliente($id);
        if ($resp == "ok") {
            return ["status" => "success", "message" => "Cliente eliminado correctamente"];
        } else {
            return ["status" => "error", "message" => "No se pudo registrar"];
        }
    }
}
