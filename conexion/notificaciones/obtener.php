<?php
include_once '../db.php';
include_once 'notificacion.php';

$usuario_id = $_POST['usuario_id'];

// Crear una instancia de la clase Notificaciones
$notificaciones = new Notificaciones($conn);

// Obtener notificaciones no leídas
$notificaciones_no_leidas = $notificaciones->obtenerNotificacionesNoLeidas($usuario_id);

// Devolver las notificaciones en formato JSON
echo json_encode($notificaciones_no_leidas);
?>
