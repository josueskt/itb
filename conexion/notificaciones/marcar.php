<?php
include_once '../db.php';
include_once 'notificacion.php';

$notificacion_id = $_POST['notificacion_id'];


$notificaciones = new Notificaciones($conn);


$respuesta = $notificaciones->marcarComoLeido($notificacion_id);


echo $respuesta;
?>
