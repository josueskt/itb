<?php
include_once '../db.php';

// Obtener los datos enviados por POST
$usuario_id = $_POST['usuario_id'];
$curso_id = $_POST['curso_id'];

// Verificar si los datos son válidos
if (empty($usuario_id) || empty($curso_id)) {
    echo "Por favor, ingrese ambos campos.";
    exit;
}

// Verificar si el usuario ya está inscrito en el curso
$sql_check = "SELECT COUNT(*) AS count FROM matriculas WHERE usuario_id = ? AND curso_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $usuario_id, $curso_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row = $result_check->fetch_assoc();

// Si ya está inscrito, mostrar un mensaje
if ($row['count'] > 0) {
    echo "El usuario ya está inscrito en este curso.";
    exit;
}

// Si no está inscrito, proceder con la inserción
$sql = "INSERT INTO matriculas (usuario_id, curso_id) VALUES (?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $curso_id);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Matrícula registrada con éxito.";
} else {
    echo "Error al registrar matrícula: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
