<?php
include_once '../db.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id) {
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM cursos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $curso = $result->fetch_assoc();
        echo json_encode($curso);
    } else {
        echo json_encode(['error' => 'Curso no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}

$conn->close();
?>
