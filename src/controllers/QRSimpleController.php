<?php
/**
 * Controlador QR Simplificado
 * Genera y maneja códigos QR para personas y eventos
 */

class QRSimpleController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Genera QR para una persona
     */
    public function generarQRPersona($id_user) {
        try {
            // Obtener información de la persona
            $stmt = $this->pdo->prepare("SELECT * FROM persona WHERE id_user = ?");
            $stmt->execute([$id_user]);
            $persona = $stmt->fetch();
            
            if (!$persona) {
                throw new Exception('Persona no encontrada');
            }
            
            // Crear directorio si no existe
            $qr_dir = 'uploads/qr/personas/';
            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0777, true);
            }
            
            // Generar contenido del QR
            $qr_content = json_encode([
                'tipo' => 'persona',
                'id' => $persona['id_user'],
                'nombre' => $persona['nom_user'],
                'tipo_persona' => $persona['tipo_persona'],
                'fecha_generacion' => date('Y-m-d H:i:s')
            ]);
            
            // Generar QR usando API gratuita
            $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qr_content);
            $qr_filename = 'persona_' . $id_user . '_' . date('YmdHis') . '.png';
            $qr_path = $qr_dir . $qr_filename;
            
            // Descargar y guardar el QR
            $qr_image = file_get_contents($qr_url);
            if ($qr_image !== false) {
                file_put_contents($qr_path, $qr_image);
                
                // Actualizar base de datos
                $stmt = $this->pdo->prepare("UPDATE persona SET codigo_qr = ? WHERE id_user = ?");
                $stmt->execute([$qr_path, $id_user]);
                
                return [
                    'success' => true,
                    'message' => 'QR generado exitosamente',
                    'path' => $qr_path
                ];
            } else {
                throw new Exception('No se pudo generar el QR');
            }
            
        } catch (Exception $e) {
            error_log("Error generando QR: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al generar QR: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Genera QR para un evento
     */
    public function generarQREvento($cod_evento) {
        try {
            // Obtener información del evento
            $stmt = $this->pdo->prepare("SELECT * FROM evento WHERE cod_evento = ?");
            $stmt->execute([$cod_evento]);
            $evento = $stmt->fetch();
            
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            
            // Crear directorio si no existe
            $qr_dir = 'uploads/qr/eventos/';
            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0777, true);
            }
            
            // Generar contenido del QR
            $qr_content = json_encode([
                'tipo' => 'evento',
                'codigo' => $evento['cod_evento'],
                'nombre' => $evento['nom_evento'],
                'fecha' => $evento['fecha_inicio'],
                'fecha_generacion' => date('Y-m-d H:i:s')
            ]);
            
            // Generar QR usando API gratuita
            $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qr_content);
            $qr_filename = 'evento_' . $cod_evento . '_' . date('YmdHis') . '.png';
            $qr_path = $qr_dir . $qr_filename;
            
            // Descargar y guardar el QR
            $qr_image = file_get_contents($qr_url);
            if ($qr_image !== false) {
                file_put_contents($qr_path, $qr_image);
                
                // Actualizar base de datos
                $stmt = $this->pdo->prepare("UPDATE evento SET qr = ? WHERE cod_evento = ?");
                $stmt->execute([$qr_path, $cod_evento]);
                
                return [
                    'success' => true,
                    'message' => 'QR del evento generado exitosamente',
                    'path' => $qr_path
                ];
            } else {
                throw new Exception('No se pudo generar el QR');
            }
            
        } catch (Exception $e) {
            error_log("Error generando QR evento: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al generar QR: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Elimina QR de una persona
     */
    public function eliminarQRPersona($id_user) {
        try {
            // Obtener ruta del QR
            $stmt = $this->pdo->prepare("SELECT codigo_qr FROM persona WHERE id_user = ?");
            $stmt->execute([$id_user]);
            $persona = $stmt->fetch();
            
            if ($persona && $persona['codigo_qr']) {
                // Eliminar archivo
                if (file_exists($persona['codigo_qr'])) {
                    unlink($persona['codigo_qr']);
                }
                
                // Actualizar base de datos
                $stmt = $this->pdo->prepare("UPDATE persona SET codigo_qr = NULL WHERE id_user = ?");
                $stmt->execute([$id_user]);
            }
            
            return [
                'success' => true,
                'message' => 'QR eliminado exitosamente'
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al eliminar QR: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verifica si una persona tiene QR
     */
    public function tieneQR($id_user) {
        try {
            $stmt = $this->pdo->prepare("SELECT codigo_qr FROM persona WHERE id_user = ?");
            $stmt->execute([$id_user]);
            $persona = $stmt->fetch();
            
            return $persona && $persona['codigo_qr'] && file_exists($persona['codigo_qr']);
            
        } catch (Exception $e) {
            error_log("Error verificando QR: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene información del QR de una persona
     */
    public function obtenerInfoQR($id_user) {
        try {
            $stmt = $this->pdo->prepare("SELECT codigo_qr FROM persona WHERE id_user = ?");
            $stmt->execute([$id_user]);
            $persona = $stmt->fetch();
            
            if ($persona && $persona['codigo_qr']) {
                $file_info = pathinfo($persona['codigo_qr']);
                return [
                    'existe' => file_exists($persona['codigo_qr']),
                    'ruta' => $persona['codigo_qr'],
                    'nombre_archivo' => $file_info['basename'],
                    'tamaño' => file_exists($persona['codigo_qr']) ? filesize($persona['codigo_qr']) : 0,
                    'fecha_generacion' => file_exists($persona['codigo_qr']) ? date('Y-m-d H:i:s', filemtime($persona['codigo_qr'])) : null
                ];
            }
            
            return ['existe' => false];
            
        } catch (Exception $e) {
            error_log("Error obteniendo info QR: " . $e->getMessage());
            return ['existe' => false];
        }
    }
}
?> 