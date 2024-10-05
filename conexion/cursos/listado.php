<?php
include_once '../db.php';

$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);

$cursos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cursos[] = $row; // Agregar cada curso al array
    }
} else {
    // Si no hay resultados, devolver un array vacío
    $cursos = [];
}

// Devolver los cursos en formato JSON
header('Content-Type: application/json'); // Asegúrate de que se envíe como JSON
echo json_encode($cursos);

$conn->close();
?>
