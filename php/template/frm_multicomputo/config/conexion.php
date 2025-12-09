<?php 
require_once "global.php";

// Create connection
$conexion = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}

