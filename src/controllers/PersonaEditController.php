<?php
/**
 * Controlador para Editar Personas
 * Maneja la actualización de información de personas
 */

require_once '../src/config/database.php';

class PersonaEditController {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    public function updatePersona() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=personas');
            exit;
        }
        
        try {
            // Validar datos requeridos
            $id_user = $_POST['id_user'] ?? '';
            $nom_user = trim($_POST['nom_user'] ?? '');
            $ciudad = $_POST['ciudad'] ?? '';
            $tipo_persona = $_POST['tipo_persona'] ?? '';
            
            if (empty($id_user) || empty($nom_user) || empty($ciudad) || empty($tipo_persona)) {
                throw new Exception('Los campos obligatorios no pueden estar vacíos');
            }
            
            // Obtener datos del formulario
            $telef_user = $_POST['telef_user'] ?? null;
            $correo_user = $_POST['correo_user'] ?? null;
            $cod_grado = $_POST['cod_grado'] ?? null;
            $grupo = $_POST['grupo'] ?? null;
            $nom_acudiente = $_POST['nom_acudiente'] ?? null;
            $telef_acudiente = $_POST['telef_acudiente'] ?? null;
            $correo_acudiente = $_POST['correo_acudiente'] ?? null;
            
            // Validar que el código de grado existe si se proporciona
            if (!empty($cod_grado)) {
                $grado_existe = $this->db->fetch(
                    "SELECT cod_grado FROM grado WHERE cod_grado = ?", 
                    [$cod_grado]
                );
                if (!$grado_existe) {
                    throw new Exception("El código de grado '$cod_grado' no existe en la base de datos");
                }
            }
            
            // Manejo de la foto
            $foto_persona = $this->handlePhotoUpload($id_user);
            
            // Actualizar persona en la base de datos
            $sql = "UPDATE persona SET
                    nom_user = ?,
                    ciudad = ?,
                    telef_user = ?,
                    correo_user = ?,
                    tipo_persona = ?,
                    cod_grado = ?,
                    grupo = ?,
                    nom_acudiente = ?,
                    telef_acudiente = ?,
                    correo_acudiente = ?,
                    foto_persona = ?,
                    fecha_edicion = NOW()
                    WHERE id_user = ?";
            
            $params = [
                $nom_user,
                $ciudad,
                $telef_user,
                $correo_user,
                $tipo_persona,
                $cod_grado,
                $grupo,
                $nom_acudiente,
                $telef_acudiente,
                $correo_acudiente,
                $foto_persona,
                $id_user
            ];
            
            $this->db->execute($sql, $params);
            
            // Mensaje de éxito
            $_SESSION['success'] = 'La persona ha sido actualizada correctamente';
            header('Location: index.php?action=personas');
            exit;
            
        } catch (Exception $e) {
            error_log("Error actualizando persona: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar la persona: ' . $e->getMessage();
            header('Location: index.php?action=personas&controller=edit&id=' . $id_user);
            exit;
        }
    }
    
    private function handlePhotoUpload($id_user) {
        // Si no se subió una nueva foto, mantener la existente
        if (!isset($_FILES['foto_persona']) || $_FILES['foto_persona']['error'] === UPLOAD_ERR_NO_FILE) {
            $sql = "SELECT foto_persona FROM persona WHERE id_user = ?";
            $result = $this->db->fetch($sql, [$id_user]);
            return $result['foto_persona'] ?? null;
        }
        
        // Validar archivo
        $file = $_FILES['foto_persona'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo');
        }
        
        // Validar tipo de archivo
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Solo se permiten archivos JPG, PNG y GIF');
        }
        
        // Validar tamaño (5MB máximo)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('El archivo excede el tamaño máximo de 5MB');
        }
        
        // Crear directorio de uploads si no existe
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generar nombre único para el archivo
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'persona_' . $id_user . '_' . uniqid() . '.' . $file_extension;
        $target_file = $upload_dir . $file_name;
        
        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            throw new Exception('Error al guardar el archivo');
        }
        
        // Eliminar foto anterior si existe
        $sql = "SELECT foto_persona FROM persona WHERE id_user = ?";
        $result = $this->db->fetch($sql, [$id_user]);
        $old_photo = $result['foto_persona'] ?? null;
        
        if ($old_photo && file_exists($old_photo)) {
            unlink($old_photo);
        }
        
        return $target_file;
    }
    
    public function getPersona($id) {
        try {
            $sql = "SELECT p.*, c.descripcion AS ciudad_nombre, 
                           tp.descripcion AS tipo_persona_nombre, 
                           g.descripcion AS grado_nombre
                    FROM persona p
                    LEFT JOIN ciudad c ON p.ciudad = c.cod_ciudad
                    LEFT JOIN tipo_persona tp ON p.tipo_persona = tp.cod_tipo
                    LEFT JOIN grado g ON p.cod_grado = g.cod_grado
                    WHERE p.id_user = ?";
            
            return $this->db->fetch($sql, [$id]);
        } catch (Exception $e) {
            error_log("Error obteniendo persona: " . $e->getMessage());
            return null;
        }
    }
    
    public function getCiudades() {
        try {
            return $this->db->fetchAll("SELECT * FROM ciudad ORDER BY descripcion");
        } catch (Exception $e) {
            error_log("Error obteniendo ciudades: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTiposPersona() {
        try {
            return $this->db->fetchAll("SELECT * FROM tipo_persona ORDER BY descripcion");
        } catch (Exception $e) {
            error_log("Error obteniendo tipos de persona: " . $e->getMessage());
            return [];
        }
    }
    
    public function getGrados() {
        try {
            return $this->db->fetchAll("SELECT * FROM grado ORDER BY cod_grado");
        } catch (Exception $e) {
            error_log("Error obteniendo grados: " . $e->getMessage());
            return [];
        }
    }
}

// Si se llama directamente este archivo
if (basename($_SERVER['PHP_SELF']) == 'PersonaEditController.php') {
    $controller = new PersonaEditController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->updatePersona();
    }
}
?>
