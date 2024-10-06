<?php
include_once '../db.php';
include_once 'notificacion.php';

$usuario_id = $_POST['usuario_id'];


$notificaciones = new Notificaciones($conn);


$notificaciones_no_leidas = $notificaciones->obtenerNotificacionesNoLeidas($usuario_id);


echo json_encode($notificaciones_no_leidas);
?>
