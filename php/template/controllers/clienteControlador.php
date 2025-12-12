<?php

class ClienteControllers
{
    public static function listarCliente()
    {
        return ClienteModels::listarCliente();
    }

    public static function agregarCliente($dato)
    {
        //Si existe id_cliente_leads 
        if (!empty($dato['id_cliente_leads'])) {
            $resp = $dato['id_cliente_leads'];
            $dato['cliente_id'] = $resp;
            ClienteModels::actualizarCliente($dato);
        } else {
            $resp = ClienteModels::agregarCliente($dato);
        }
        if ($resp > 0) {
            $id_cliente = $resp;
            TelefonoAdicionalControllers::agregarNumeroAdicional($dato, $id_cliente);
            if (isset($dato['ip_usuario']) && !empty($dato['ip_usuario'])) {
                $marketing = new Marketing_trackingControllers();
                $id_marketing = $marketing->consultarClickExistete($dato);
                $marketing->updateClickMarketing($id_marketing['id'], $id_cliente);
            }
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

    public static function consultarCliente($valor)
    {
        $resp = ClienteModels::consultarCliente($valor);

        if ($resp) {
            return [
                "status" => "existe",
                "message" => "El cliente ya se encuentra registrado.",
                "cliente" => $resp["datos"],
                "numeros_adicionales" => $resp["numeros_adicionales"]
            ];
        } else {
            return [
                "status" => "no_existe",
                "message" => "Cliente no encontrado."
            ];
        }
    }
}
