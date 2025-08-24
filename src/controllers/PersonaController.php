<?php
/**
 * Controlador de Personas Simplificado
 * Maneja la creación, edición y gestión de personas
 */

class PersonaController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Obtiene todas las personas
     */
    public function getAllPersonas() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM persona ORDER BY nom_user");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo personas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene una persona por ID
     */
    public function getPersonaById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM persona WHERE id_user = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error obteniendo persona: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Crea una nueva persona
     */
    public function crearPersona($datos) {
        try {
            $sql = "INSERT INTO persona (nom_user, correo_user, contrasena_user, tipo_persona, telef_user, ciudad) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['password'],
                $datos['tipo'],
                $datos['telefono'] ?? null,
                $datos['ciudad'] ?? null
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creando persona: " . $e->getMessage());
            throw new Exception('Error al crear persona: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualiza una persona existente
     */
    public function actualizarPersona($id, $datos) {
        try {
            $sql = "UPDATE persona SET 
                    nom_user = ?, 
                    correo_user = ?, 
                    tipo_persona = ?, 
                    telef_user = ?, 
                    ciudad = ? 
                    WHERE id_user = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['tipo'],
                $datos['telefono'] ?? null,
                $datos['ciudad'] ?? null,
                $id
            ]);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error actualizando persona: " . $e->getMessage());
            throw new Exception('Error al actualizar persona: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina una persona
     */
    public function eliminarPersona($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM persona WHERE id_user = ?");
            $stmt->execute([$id]);
            return true;
            
        } catch (Exception $e) {
            error_log("Error eliminando persona: " . $e->getMessage());
            throw new Exception('Error al eliminar persona: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtiene personas por tipo
     */
    public function getPersonasPorTipo($tipo) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM persona WHERE tipo_persona = ? ORDER BY nom_user");
            $stmt->execute([$tipo]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo personas por tipo: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verifica si el email ya existe
     */
    public function emailExiste($email, $excluirId = null) {
        try {
            if ($excluirId) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM persona WHERE correo_user = ? AND id_user != ?");
                $stmt->execute([$email, $excluirId]);
            } else {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM persona WHERE correo_user = ?");
                $stmt->execute([$email]);
            }
            
            return $stmt->fetchColumn() > 0;
            
        } catch (Exception $e) {
            error_log("Error verificando email: " . $e->getMessage());
            return false;
        }
    }
}
?>