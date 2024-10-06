<?php
include_once '../db.php';

$sql = "SELECT * FROM cursos";
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
