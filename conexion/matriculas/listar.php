<?php
include_once '../db.php';

$sql = "SELECT matriculas.id, usuarios.nombre, usuarios.email, cursos.nombre AS curso 
        FROM matriculas
        INNER JOIN usuarios ON matriculas.usuario_id = usuarios.id
        INNER JOIN cursos ON matriculas.curso_id = cursos.id
        ORDER BY matriculas.id DESC";
$result = $conn->query($sql);

$cursos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }
} else {
    
    $cursos = [];
}


header('Content-Type: application/json'); 
echo json_encode($cursos);

$conn->close();
?>
