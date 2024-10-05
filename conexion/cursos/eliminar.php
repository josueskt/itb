<?php
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM cursos WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Curso eliminado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
