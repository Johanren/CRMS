<?php

class MensajeModels
{
    public static function agregarMensaje($dato)
    {
        $sql = "INSERT INTO mensaje (fec_mns, tip_mns,men_mns,tem_mns) VALUES (NOW(),?,?,?)";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $dato['tema']);
        $stmt->bindParam(2, $dato['mensaje']);
        $stmt->bindParam(3, $dato['tipo']);
        if ($stmt->execute()) {
           return ["ok" => "ok"];
        }else{
            return "error";
        }
        
    }

    public static function listarMensaje(){
        $sql = "SELECT m.id_mns AS id, tp.tip_mns AS tipo, tm.tem_mns AS tema, m.men_mns AS mensaje FROM mensaje m INNER JOIN tipo_mensaje tp ON m.tip_mns = tp.id_tp_mns INNER JOIN tem_mns tm ON tm.id_tem_mns = m.tem_mns; ";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        if ($stmt->execute()) {
           return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return "error";
        }
    }

    public static function obtenerMensaje($id){
        $sql = "SELECT m.id_mns AS id, m.tip_mns AS tipo, m.tem_mns AS tema, m.men_mns AS mensaje FROM mensaje m WHERE m.id_mns = ?; ";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
           return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return "error";
        }
    }

    public static function actualizarMesaje($dato)
    {
        $sql = "UPDATE mensaje SET tip_mns = ?,men_mns = ?,tem_mns = ? WHERE id_mns = ?";
        $conn = new Conexion();
        $conectar = $conn->conectar();
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $dato['tema']);
        $stmt->bindParam(2, $dato['mensaje']);
        $stmt->bindParam(3, $dato['tipo']);
        $stmt->bindParam(4, $dato['id']);
        if ($stmt->execute()) {
           return ["ok" => "ok"];
        }else{
            return "error";
        }
        
    }

    public static function eliminar_mensaje($id){
            $sql = "DELETE FROM mensaje WHERE id_mns = ?; ";
            $conn = new Conexion();
            $conectar = $conn->conectar();
            $stmt = $conectar->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute()) {
               return ["ok" => "ok"];
            }else{
                return "error";
            }
        }

    static function listarMensajesParametrizados() {
            $sql = "
            SELECT 
                tp.tip_co AS tipo,
                m.men_mns AS mensaje
            FROM mensaje m
            INNER JOIN tipo_mensaje tp 
                ON tp.id_tp_mns = m.tip_mns
        ";
                $conn = new Conexion();
                $conectar = $conn->conectar();
                $stmt = $conectar->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}