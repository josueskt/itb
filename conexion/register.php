<?php
// Incluir la conexión a la base de datos
include_once 'db.php';

// Obtener los datos enviados por POST
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

// Validar si los datos son válidos
if (empty($nombre) || empty($email) || empty($contrasena)) {
    echo "Por favor, complete todos los campos.";
    exit;
}

// Verificar si el email ya existe
$sql_check_email = "SELECT id FROM usuarios WHERE email = ?";
$stmt_check_email = $conn->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$result = $stmt_check_email->get_result();

if ($result->num_rows > 0) {
    echo "El correo electrónico ya está registrado.";
    exit;
}

// Encriptar la contraseña
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar el nuevo usuario en la base de datos
$sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $email, $contrasena_hash);

// Ejecutar la consulta
if ($stmt->execute()) {
    
    echo "Usuario registrado con éxito.";
} else {
    echo "Error al registrar el usuario: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
