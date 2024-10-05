<?php
$servidor = "localhost";
$usuario = "root";  // Cambia según tu configuración de MySQL
$clave = "";        // Cambia si tienes contraseña para MySQL
$base_datos = "proyecto"; // Nombre de tu base de datos :3 

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
