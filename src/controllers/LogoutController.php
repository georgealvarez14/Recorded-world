<?php
/**
 * Controlador de Logout Simplificado
 * Maneja el cierre de sesión de usuarios
 */

class LogoutController {
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        // Destruir todas las variables de sesión
        session_start();
        session_unset();
        session_destroy();
        
        // Redirigir a la página principal
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
     * Requiere que el usuario esté logueado
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?accion=login');
            exit;
        }
    }
}
?>