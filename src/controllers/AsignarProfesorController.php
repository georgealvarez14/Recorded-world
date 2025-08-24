<?php
/**
 * Controlador para Asignar Profesores Simplificado
 * Permite asignar profesores a eventos específicos
 */

class AsignarProfesorController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Asigna un profesor a un evento
     */
    public function asignarProfesor($cod_evento, $id_profesor) {
        try {
            // Verificar que el evento existe
            $stmt = $this->pdo->prepare("SELECT cod_evento, nom_evento FROM evento WHERE cod_evento = ?");
            $stmt->execute([$cod_evento]);
            $evento = $stmt->fetch();
            
            if (!$evento) {
                throw new Exception('El evento seleccionado no existe');
            }
            
            // Verificar que la persona es un profesor
            $stmt = $this->pdo->prepare("SELECT id_user, nom_user FROM persona WHERE id_user = ? AND tipo_persona = 'DOC'");
            $stmt->execute([$id_profesor]);
            $profesor = $stmt->fetch();
            
            if (!$profesor) {
                throw new Exception('La persona seleccionada no es un profesor');
            }
            
            // Verificar que no esté ya asignado
            $stmt = $this->pdo->prepare("SELECT * FROM personal_evento WHERE cod_evento = ? AND id_user = ?");
            $stmt->execute([$cod_evento, $id_profesor]);
            $asignacionExistente = $stmt->fetch();
            
            if ($asignacionExistente) {
                throw new Exception('Este profesor ya está asignado a este evento');
            }
            
            // Asignar profesor al evento
            $stmt = $this->pdo->prepare("INSERT INTO personal_evento (cod_evento, id_user) VALUES (?, ?)");
            $stmt->execute([$cod_evento, $id_profesor]);
            
            return [
                'success' => true,
                'message' => 'Profesor "' . $profesor['nom_user'] . '" asignado exitosamente al evento "' . $evento['nom_evento'] . '"'
            ];
            
        } catch (Exception $e) {
            error_log("Error asignando profesor: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al asignar profesor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Desasigna un profesor de un evento
     */
    public function desasignarProfesor($cod_evento, $id_profesor) {
        try {
            // Eliminar asignación
            $stmt = $this->pdo->prepare("DELETE FROM personal_evento WHERE cod_evento = ? AND id_user = ?");
            $stmt->execute([$cod_evento, $id_profesor]);
            
            return [
                'success' => true,
                'message' => 'Profesor desasignado exitosamente del evento'
            ];
            
        } catch (Exception $e) {
            error_log("Error desasignando profesor: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al desasignar profesor: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene todos los eventos
     */
    public function getEventos() {
        try {
            $stmt = $this->pdo->prepare("SELECT cod_evento, nom_evento, fecha_inicio FROM evento ORDER BY fecha_inicio DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo eventos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene todos los profesores
     */
    public function getProfesores() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_user, nom_user, correo_user FROM persona WHERE tipo_persona = 'DOC' ORDER BY nom_user");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo profesores: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene todas las asignaciones
     */
    public function getAsignaciones() {
        try {
            $sql = "SELECT pe.cod_evento, pe.id_user, 
                           e.nom_evento, e.fecha_inicio,
                           p.nom_user, p.correo_user
                    FROM personal_evento pe
                    JOIN evento e ON pe.cod_evento = e.cod_evento
                    JOIN persona p ON pe.id_user = p.id_user
                    ORDER BY e.fecha_inicio DESC, p.nom_user";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo asignaciones: " . $e->getMessage());
            return [];
        }
    }
}
?> 
?> 