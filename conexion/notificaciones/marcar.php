<?php
include_once '../db.php';
include_once 'notificacion.php';

$notificacion_id = $_POST['notificacion_id'];

// Crear una instancia de la clase Notificaciones
$notificaciones = new Notificaciones($conn);

// Marcar la notificación como leída
$respuesta = $notificaciones->marcarComoLeido($notificacion_id);

// Devolver respuesta
echo $respuesta;
?>
