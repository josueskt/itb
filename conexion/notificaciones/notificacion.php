<?php
// Incluir la conexión a la base de datos
include_once '../db.php';

class Notificaciones
{
    private $conn;

    // Constructor para recibir la conexión a la base de datos
    public function __construct($db_connection)
    {
        $this->conn = $db_connection;
    }

    // Método para crear una nueva notificación
    public function crearNotificacion($usuario_id, $mensaje)
    {
        // Verificamos si el usuario_id y mensaje son válidos
        if (empty($usuario_id) || empty($mensaje)) {
            return "El usuario y el mensaje son obligatorios.";
        }

        // Preparamos la consulta SQL para insertar la notificación
        $sql = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (?, ?)";

        // Preparamos la sentencia
        $stmt = $this->conn->prepare($sql);
        
        // Ligamos los parámetros (usuario_id y mensaje)
        $stmt->bind_param("is", $usuario_id, $mensaje);
        
        // Ejecutamos la consulta
        if ($stmt->execute()) {
            return "Notificación enviada con éxito.";
        } else {
            return "Error al enviar la notificación: " . $stmt->error;
        }
    }

    // Método para obtener las notificaciones no leídas de un usuario
    public function obtenerNotificacionesNoLeidas($usuario_id)
    {
        $sql = "SELECT id, mensaje, fecha_envio FROM notificaciones 
                WHERE usuario_id = ? AND leido = 0
                ORDER BY fecha_envio DESC";
        
        // Preparamos la consulta
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Almacenamos las notificaciones en un array
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
        
        return $notificaciones;
    }

    // Método para marcar las notificaciones como leídas
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
