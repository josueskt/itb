<?php
include_once '../db.php';


$usuario_id = $_POST['usuario_id'];
$curso_id = $_POST['curso_id'];


if (empty($usuario_id) || empty($curso_id)) {
    echo "Por favor, ingrese ambos campos.";
    exit;
}


$sql_check = "SELECT COUNT(*) AS count FROM matriculas WHERE usuario_id = ? AND curso_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $usuario_id, $curso_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row = $result_check->fetch_assoc();


if ($row['count'] > 0) {
    echo "El usuario ya está inscrito en este curso.";
    exit;
}


$sql = "INSERT INTO matriculas (usuario_id, curso_id) VALUES (?, ?)";


$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $curso_id);


if ($stmt->execute()) {
    echo "Matrícula registrada con éxito.";
} else {
    echo "Error al registrar matrícula: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
