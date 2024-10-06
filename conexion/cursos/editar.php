<?php
include_once '../db.php';

$id = isset($_POST['id']) ? $_POST['id'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

if ($id && $nombre && $fecha_inicio && $fecha_fin) {
    $id = $conn->real_escape_string($id);
    $nombre = $conn->real_escape_string($nombre);
    $descripcion = $conn->real_escape_string($descripcion);
    $fecha_inicio = $conn->real_escape_string($fecha_inicio);
    $fecha_fin = $conn->real_escape_string($fecha_fin);

    $updateSql = "UPDATE cursos SET 
                    nombre = '$nombre', 
                    descripcion = '$descripcion', 
                    fecha_inicio = '$fecha_inicio', 
                    fecha_fin = '$fecha_fin' 
                  WHERE id = $id";

    if ($conn->query($updateSql) === TRUE) {
        echo "Curso actualizado exitosamente.";
    } else {
        echo "Error al actualizar el curso: " . $conn->error;
    }
} else {
    echo "Por favor, completa todos los campos.";
}

$conn->close();
?>
