<?php
/**
 * Controlador de Página Principal Simplificado
 * Maneja la página de inicio y landing
 */

class LandingController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Muestra la página principal
     */
    public function showLanding() {
        // Obtener estadísticas básicas
        $stats = $this->getEstadisticas();
        
        // Incluir la vista
        include '../src/vistas/pagina_principal.php';
    }
    
    /**
     * Obtiene estadísticas básicas del sistema
     */
    public function getEstadisticas() {
        try {
            $stats = [];
            
            // Total de personas
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM persona");
            $stmt->execute();
            $stats['total_personas'] = $stmt->fetchColumn();
            
            // Total de eventos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM evento");
            $stmt->execute();
            $stats['total_eventos'] = $stmt->fetchColumn();
            
            // Estudiantes
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM persona WHERE tipo_persona = 'EST'");
            $stmt->execute();
            $stats['estudiantes'] = $stmt->fetchColumn();
            
            // Docentes
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM persona WHERE tipo_persona = 'DOC'");
            $stmt->execute();
            $stats['docentes'] = $stmt->fetchColumn();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas: " . $e->getMessage());
            return [
                'total_personas' => 0,
                'total_eventos' => 0,
                'estudiantes' => 0,
                'docentes' => 0
            ];
        }
    }
    
    /**
     * Verifica si el usuario está logueado
     */
    public function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }
    
    /**
     * Obtiene el tipo de usuario logueado
     */
    public function getUserType() {
        return $_SESSION['usuario_tipo'] ?? '';
    }
}
?> 