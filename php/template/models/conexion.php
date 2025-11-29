<?php
class Conexion{
    public function conectar(){
		$pdo = new PDO("mysql:host=localhost;dbname=crm","root","");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Configura la codificación de caracteres para la conexión
		$pdo->exec("set names utf8mb4");
		return $pdo;
	}
}