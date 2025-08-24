<?php
// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: materias.php');
    exit();
}

// Verificar que se recibieron los datos necesarios
if (!isset($_POST['id_materia']) || !isset($_POST['descripcion'])) {
    header('Location: materias.php?error=datos_faltantes');
    exit();
}

// Obtener y limpiar los datos del formulario
$id_materia = trim($_POST['id_materia']);
$descripcion = trim($_POST['descripcion']);

// Validar que los campos no estén vacíos
if (empty($id_materia) || empty($descripcion)) {
    header('Location: Editar_materia.php?id=' . $id_materia . '&error=campos_vacios');
    exit();
}

try {
    // Conexión a la base de datos
    $conexion = new PDO('mysql:host=localhost;dbname=registro;charset=utf8', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que la materia existe antes de actualizar
    $stmt_verificar = $conexion->prepare("SELECT cod_categoria FROM materias WHERE cod_categoria = ?");
    $stmt_verificar->execute([$id_materia]);
    
    if (!$stmt_verificar->fetch()) {
        header('Location: materias.php?error=materia_no_encontrada');
        exit();
    }
    
    // Preparar la consulta de actualización
    $sql = "UPDATE materias SET descripcion = ? WHERE cod_categoria = ?";
    $stmt = $conexion->prepare($sql);
    
    // Ejecutar la actualización
    $resultado = $stmt->execute([$descripcion, $id_materia]);
    
    if ($resultado) {
        // Verificar si realmente se actualizó algún registro (en caso de error, se redirigirá a la página de edición)
        if ($stmt->rowCount() > 0) {
            // Redireccionar con mensaje de éxito
            header('Location: Editar_materia.php?id=' . $id_materia . '&actualizado=1');
        } else {
            // No se realizaron cambios (probablemente los datos eran iguales)
            header('Location: Editar_materia.php?id=' . $id_materia . '&sin_cambios=1');
        }
    } else {
        // Error en la actualización
        header('Location: Editar_materia.php?id=' . $id_materia . '&error=actualizacion_fallida');
    }
    
} catch (PDOException $e) {
    // Log del error (en un entorno de producción, esto debería ir a un archivo de log)
    error_log("Error en Editar_DB_2025.php: " . $e->getMessage());
    
    // Redireccionar con mensaje de error
    header('Location: Editar_materia.php?id=' . $id_materia . '&error=bd');
    
} catch (Exception $e) {
    // Manejar cualquier otro error
    error_log("Error general en Editar_DB_2025.php: " . $e->getMessage());
    header('Location: Editar_materia.php?id=' . $id_materia . '&error=general');
}

// Cerrar la conexión
$conexion = null;
exit();
?>