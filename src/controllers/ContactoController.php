<?php
/**
 * Controlador de Contacto Simplificado
 * Maneja el envío y gestión de mensajes de contacto
 */

class ContactoController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Envía un mensaje de contacto
     */
    public function enviarMensaje($datos) {
        try {
            // Validar datos requeridos
            if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['mensaje'])) {
                throw new Exception('Todos los campos son obligatorios');
            }
            
            // Validar formato de email
            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El formato del email no es válido');
            }
            
            // Insertar mensaje en la base de datos
            $sql = "INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje, fecha_envio) 
                    VALUES (?, ?, ?, ?, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['asunto'] ?? 'Consulta General',
                $datos['mensaje']
            ]);
            
            return [
                'success' => true,
                'message' => 'Mensaje enviado exitosamente. Nos pondremos en contacto contigo pronto.'
            ];
            
        } catch (Exception $e) {
            error_log("Error enviando mensaje: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al enviar mensaje: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene todos los mensajes (para administradores)
     */
    public function getMensajes() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo mensajes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Marca un mensaje como leído
     */
    public function marcarComoLeido($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log("Error marcando mensaje como leído: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Elimina un mensaje
     */
    public function eliminarMensaje($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM mensajes_contacto WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log("Error eliminando mensaje: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene estadísticas de mensajes
     */
    public function getEstadisticas() {
        try {
            $stats = [];
            
            // Total de mensajes
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mensajes_contacto");
            $stmt->execute();
            $stats['total'] = $stmt->fetchColumn();
            
            // Mensajes no leídos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mensajes_contacto WHERE leido = 0");
            $stmt->execute();
            $stats['no_leidos'] = $stmt->fetchColumn();
            
            // Mensajes de hoy
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mensajes_contacto WHERE DATE(fecha_envio) = CURDATE()");
            $stmt->execute();
            $stats['hoy'] = $stmt->fetchColumn();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas: " . $e->getMessage());
            return ['total' => 0, 'no_leidos' => 0, 'hoy' => 0];
        }
    }
}
?> 