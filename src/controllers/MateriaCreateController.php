<?php
/**
 * Controlador para Crear Materias
 * Maneja la creación de nuevas materias en el sistema
 */

require_once '../src/config/database.php';

class MateriaCreateController {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    public function createMateria() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=materias');
            exit;
        }
        
        try {
            // Validar datos requeridos
            $codigo = trim($_POST['codigo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            
            if (empty($codigo) || empty($descripcion)) {
                throw new Exception('Los campos código y descripción son obligatorios');
            }
            
            // Validar longitud del código
            if (strlen($codigo) > 10) {
                throw new Exception('El código no puede tener más de 10 caracteres');
            }
            
            // Validar longitud de la descripción
            if (strlen($descripcion) > 100) {
                throw new Exception('La descripción no puede tener más de 100 caracteres');
            }
            
            // Verificar si el código ya existe
            $existingMateria = $this->db->fetch(
                "SELECT cod_categoria FROM materias WHERE cod_categoria = ?", 
                [$codigo]
            );
            
            if ($existingMateria) {
                throw new Exception('El código de materia ya existe. Por favor, usa otro código.');
            }
            

            
            // Insertar nueva materia
            $sql = "INSERT INTO materias (cod_categoria, descripcion) 
                    VALUES (?, ?)";
            
            $params = [
                strtoupper($codigo),
                $descripcion
            ];
            
            $this->db->execute($sql, $params);
            
            // Mensaje de éxito
            $_SESSION['success'] = 'Materia "' . $descripcion . '" creada exitosamente con código "' . strtoupper($codigo) . '"';
            header('Location: index.php?action=materias');
            exit;
            
        } catch (Exception $e) {
            error_log("Error creando materia: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear la materia: ' . $e->getMessage();
            header('Location: index.php?action=materias&controller=create');
            exit;
        }
    }
    
    public function getMateriasExistentes() {
        try {
            return $this->db->fetchAll("SELECT cod_categoria, descripcion FROM materias ORDER BY cod_categoria");
        } catch (Exception $e) {
            error_log("Error obteniendo materias existentes: " . $e->getMessage());
            return [];
        }
    }
    
    public function validateCodigo($codigo) {
        try {
            $existing = $this->db->fetch(
                "SELECT cod_categoria FROM materias WHERE cod_categoria = ?", 
                [strtoupper($codigo)]
            );
            return !$existing; // Retorna true si el código está disponible
        } catch (Exception $e) {
            error_log("Error validando código: " . $e->getMessage());
            return false;
        }
    }
    
    public function getEstadisticas() {
        try {
            $total = $this->db->fetch("SELECT COUNT(*) as total FROM materias");
            
            return [
                'total' => $total['total'] ?? 0,
                'activas' => $total['total'] ?? 0 // Todas las materias están activas por defecto
            ];
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas: " . $e->getMessage());
            return ['total' => 0, 'activas' => 0];
        }
    }
}

// Si se llama directamente este archivo
if (basename($_SERVER['PHP_SELF']) == 'MateriaCreateController.php') {
    $controller = new MateriaCreateController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->createMateria();
    }
}
?> 