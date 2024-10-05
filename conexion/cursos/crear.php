<?php
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "INSERT INTO cursos (nombre, descripcion, fecha_inicio, fecha_fin) 
            VALUES ('$nombre', '$descripcion', '$fecha_inicio', '$fecha_fin')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo curso creado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
