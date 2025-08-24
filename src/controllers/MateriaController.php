<?php
/**
 * Controlador de Materias Simplificado
 * Maneja la gestión de materias
 */

class MateriaController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Obtiene todas las materias
     */
    public function getAllMaterias() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM materias ORDER BY descripcion");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo materias: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene una materia por ID
     */
    public function getMateriaById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM materias WHERE cod_categoria = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error obteniendo materia: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Crea una nueva materia
     */
    public function crearMateria($datos) {
        try {
            $sql = "INSERT INTO materias (cod_categoria, descripcion) VALUES (?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['codigo'],
                $datos['descripcion']
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creando materia: " . $e->getMessage());
            throw new Exception('Error al crear materia: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualiza una materia existente
     */
    public function actualizarMateria($id, $datos) {
        try {
            $sql = "UPDATE materias SET descripcion = ? WHERE cod_categoria = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['descripcion'],
                $id
            ]);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error actualizando materia: " . $e->getMessage());
            throw new Exception('Error al actualizar materia: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina una materia
     */
    public function eliminarMateria($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM materias WHERE cod_categoria = ?");
            $stmt->execute([$id]);
            return true;
            
        } catch (Exception $e) {
            error_log("Error eliminando materia: " . $e->getMessage());
            throw new Exception('Error al eliminar materia: ' . $e->getMessage());
        }
    }
}
?>