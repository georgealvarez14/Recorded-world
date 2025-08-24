<?php
// Obtener datos de la persona
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM persona WHERE id_user = ?");
$stmt->execute([$id]);
$persona = $stmt->fetch();

if (!$persona) {
    header('Location: index.php?accion=personas');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Persona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Inicio</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">✏️ Editar Persona</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Formulario -->
                        <form method="POST" action="index.php?accion=actualizar_persona">
                            <input type="hidden" name="id" value="<?php echo $persona['id_user']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($persona['nom_user']); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($persona['correo_user']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de persona *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="ADM" <?php echo $persona['tipo_persona'] == 'ADM' ? 'selected' : ''; ?>>
                                        Administrador
                                    </option>
                                    <option value="DOC" <?php echo $persona['tipo_persona'] == 'DOC' ? 'selected' : ''; ?>>
                                        Docente
                                    </option>
                                    <option value="EST" <?php echo $persona['tipo_persona'] == 'EST' ? 'selected' : ''; ?>>
                                        Estudiante
                                    </option>
                                    <option value="ACU" <?php echo $persona['tipo_persona'] == 'ACU' ? 'selected' : ''; ?>>
                                        Acudiente
                                    </option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?accion=personas" class="btn btn-secondary me-md-2">
                                    ❌ Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    💾 Actualizar Persona
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 