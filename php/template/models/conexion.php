<?php
class Conexion{
    public function conectar(){
		$pdo = new PDO("mysql:host=195.35.61.37;dbname=u941333950_crm_dev", "u941333950_crm_dev", "HG9;@#B?d4");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//remplazar para produccion u941333950_crm_dev a u941333950_ucrm y la contraseña de HG9;@#B?d4 a 0^v8j$~hH
		// Configura la codificación de caracteres para la conexión
		$pdo->exec("set names utf8mb4");
		// Zona horaria Colombia - Bogotá (AFECTA CURRENT_TIMESTAMP)
        $pdo->exec("SET time_zone = '-05:00'");
		return $pdo;
	}
}