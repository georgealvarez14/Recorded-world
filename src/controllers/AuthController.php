<?php
/**
 * Controlador de Autenticación Simplificado
 * Maneja login, logout y verificación de usuarios
 */

class AuthController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Procesa el login del usuario
     */
    public function login($email, $password) {
        try {
            // Buscar usuario por email
            $stmt = $this->pdo->prepare("SELECT * FROM persona WHERE correo_user = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            // Verificar si existe y la contraseña es correcta
            if ($usuario && $usuario['contrasena_user'] === $password) {
                // Guardar datos en sesión
                $_SESSION['usuario_id'] = $usuario['id_user'];
                $_SESSION['usuario_nombre'] = $usuario['nom_user'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_persona'];
                $_SESSION['usuario_email'] = $usuario['correo_user'];
                
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    /**
     * Verifica si el usuario está logueado
     */
    public function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }
    
    /**
     * Verifica si el usuario es administrador
     */
    public function isAdmin() {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'ADM';
    }
    
    /**
     * Verifica si el usuario es docente
     */
    public function isTeacher() {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'DOC';
    }
    
    /**
     * Verifica si el usuario es estudiante
     */
    public function isStudent() {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'EST';
    }
    
    /**
     * Verifica si el usuario es acudiente
     */
    public function isGuardian() {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'ACU';
    }
    
    /**
     * Obtiene el tipo de usuario
     */
    public function getUserType() {
        return $_SESSION['usuario_tipo'] ?? '';
    }
    
    /**
     * Obtiene el nombre del usuario
     */
    public function getUserName() {
        return $_SESSION['usuario_nombre'] ?? '';
    }
    
    /**
     * Obtiene el ID del usuario
     */
    public function getUserId() {
        return $_SESSION['usuario_id'] ?? '';
    }
}
?>