<?php
include_once '../db.php';

$usuario_id = $_POST['usuario_id'];
$curso_id = $_POST['curso_id'];

if (empty($usuario_id) || empty($curso_id)) {
    echo "Por favor, ingrese ambos campos.";
    exit;
}

// Verificamos si ya está inscrito en el curso
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

// Insertamos la matrícula
$sql = "INSERT INTO matriculas (usuario_id, curso_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $curso_id);

if ($stmt->execute()) {
    echo "Matrícula registrada con éxito.";

    // Obtener el nombre del curso para la notificación
    $sql_curso = "SELECT nombre FROM cursos WHERE id = ?";
    $stmt_curso = $conn->prepare($sql_curso);
    $stmt_curso->bind_param("i", $curso_id);
    $stmt_curso->execute();
    $result_curso = $stmt_curso->get_result();

    if ($result_curso->num_rows > 0) {
        $curso = $result_curso->fetch_assoc();
        $nombre_curso = $curso['nombre'];

        // Insertar una notificación para el usuario
        $mensaje = "Te has inscrito en el curso: " . $nombre_curso . " con éxito.";
        $sql_notificacion = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)";
        $stmt_notificacion = $conn->prepare($sql_notificacion);
        $stmt_notificacion->bind_param("is", $usuario_id, $mensaje);

        if ($stmt_notificacion->execute()) {
            echo " Notificación enviada con éxito.";
        } else {
            echo " Error al enviar la notificación: " . $stmt_notificacion->error;
        }

        $stmt_notificacion->close();
    } else {
        echo "Error al obtener el nombre del curso.";
    }

    $stmt_curso->close();

} else {
    echo "Error al registrar matrícula: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
