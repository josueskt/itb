<?php
include_once 'db.php';

$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

if (empty($email) || empty($contrasena)) {
    echo json_encode(['message' => 'Por favor, complete ambos campos']);
    exit;
}

$sql = "SELECT id, nombre, contrasena FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($contrasena, $row['contrasena'])) {
        $id_usuario = $row['id'];
        $nombre_usuario = $row['nombre'];
        
        $mensaje = "Inicio de sesión exitoso para $nombre_usuario";
        $sql_notificacion = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)";
        $stmt_notificacion = $conn->prepare($sql_notificacion);
        $stmt_notificacion->bind_param("is", $id_usuario, $mensaje);
        $stmt_notificacion->execute();
        $stmt_notificacion->close();
        
        echo json_encode([
            'message' => 'Login exitoso',
            'id_usuario' => $id_usuario,
            'usuario' => $nombre_usuario
        ]);
    } else {
        echo json_encode(['message' => 'Credenciales incorrectas']);
    }
} else {
    echo json_encode(['message' => 'Credenciales incorrectas']);
}

$stmt->close();
$conn->close();
?>
