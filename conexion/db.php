<?php
$servidor = "localhost";
$usuario = "root";  
$clave = "";       
$base_datos = "proyecto"; 

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
