<?php
/**
 * Controlador de Acudiente Simplificado
 * Maneja las funcionalidades específicas de acudientes
 */

class AcudienteController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Obtiene los estudiantes asociados a un acudiente
     */
    public function getEstudiantesAcudiente($acudiente_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM persona 
                WHERE tipo_persona = 'EST' 
                AND cc_acudiente = (SELECT cc_acudiente FROM persona WHERE id_user = ? AND tipo_persona = 'ACU')
                ORDER BY nom_user
            ");
            $stmt->execute([$acudiente_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo estudiantes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene los eventos de un estudiante específico
     */
    public function getEventosEstudiante($estudiante_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT e.*, pe.fecha_registro 
                FROM evento e
                JOIN participante_evento pe ON e.cod_evento = pe.cod_evento
                WHERE pe.id_user = ?
                ORDER BY e.fecha_inicio DESC
            ");
            $stmt->execute([$estudiante_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo eventos del estudiante: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Actualiza el perfil del acudiente
     */
    public function actualizarPerfil($acudiente_id, $datos) {
        try {
            $sql = "UPDATE persona SET 
                    nom_user = ?, 
                    correo_user = ?, 
                    telef_user = ?, 
                    ciudad = ? 
                    WHERE id_user = ? AND tipo_persona = 'ACU'";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['telefono'] ?? null,
                $datos['ciudad'] ?? null,
                $acudiente_id
            ]);
            
            return [
                'success' => true,
                'message' => 'Perfil actualizado exitosamente'
            ];
            
        } catch (Exception $e) {
            error_log("Error actualizando perfil: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar perfil: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene información detallada de un estudiante
     */
    public function getInfoEstudiante($estudiante_id, $acudiente_id) {
        try {
            // Verificar que el estudiante pertenece al acudiente
            $stmt = $this->pdo->prepare("
                SELECT p.* FROM persona p
                JOIN persona acu ON acu.cc_acudiente = p.cc_acudiente
                WHERE p.id_user = ? AND acu.id_user = ? AND p.tipo_persona = 'EST'
            ");
            $stmt->execute([$estudiante_id, $acudiente_id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error obteniendo info estudiante: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene estadísticas del acudiente
     */
    public function getEstadisticasAcudiente($acudiente_id) {
        try {
            $stats = [];
            
            // Total de estudiantes
            $estudiantes = $this->getEstudiantesAcudiente($acudiente_id);
            $stats['total_estudiantes'] = count($estudiantes);
            
            // Total de eventos
            $total_eventos = 0;
            foreach ($estudiantes as $estudiante) {
                $eventos = $this->getEventosEstudiante($estudiante['id_user']);
                $total_eventos += count($eventos);
            }
            $stats['total_eventos'] = $total_eventos;
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas acudiente: " . $e->getMessage());
            return ['total_estudiantes' => 0, 'total_eventos' => 0];
        }
    }
}
?> 