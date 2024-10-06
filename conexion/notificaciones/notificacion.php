<?php

include_once '../db.php';

class Notificaciones
{
    private $conn;

   
    public function __construct($db_connection)
    {
        $this->conn = $db_connection;
    }

    
    public function crearNotificacion($usuario_id, $mensaje)
    {
      
        if (empty($usuario_id) || empty($mensaje)) {
            return "El usuario y el mensaje son obligatorios.";
        }

       
        $sql = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)";

    
        $stmt = $this->conn->prepare($sql);
        
  
        $stmt->bind_param("is", $usuario_id, $mensaje);
        
    
        if ($stmt->execute()) {
            return "Notificación enviada con éxito.";
        } else {
            return "Error al enviar la notificación: " . $stmt->error;
        }
    }

   
    public function obtenerNotificacionesNoLeidas($usuario_id)
    {
        $sql = "SELECT id, mensaje, fecha_envio FROM notificaciones 
                WHERE usuario_id = ? AND leido = 0
                ORDER BY fecha_envio DESC";
        
   
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
       
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
        
        return $notificaciones;
    }

   
    public function marcarComoLeido($notificacion_id)
    {
        $sql = "UPDATE notificaciones SET leido = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $notificacion_id);

        if ($stmt->execute()) {
            return "Notificación marcada como leída.";
        } else {
            return "Error al marcar la notificación como leída: " . $stmt->error;
        }
    }
}
