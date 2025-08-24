<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grado - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos - ADMIN</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Dashboard</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2>✏️ Editar Grado</h2>
                        <p class="mb-0">Modificar información del grado</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📝 Información del Grado</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_grado_admin">
                            <input type="hidden" name="codigo" value="<?php echo $grado['cod_grado']; ?>">
                            
                            <div class="mb-3">
                                <label for="codigo_display" class="form-label">Código del Grado</label>
                                <input type="number" class="form-control" id="codigo_display" 
                                       value="<?php echo $grado['cod_grado']; ?>" readonly>
                                <div class="form-text">El código no se puede modificar</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                       value="<?php echo htmlspecialchars($grado['descripcion']); ?>" 
                                       required placeholder="Ej: Sexto 1, Séptimo 2, Octavo 3">
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=crud_grados" class="btn btn-secondary me-md-2">
                                            ❌ Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            💾 Actualizar Grado
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ℹ️ Información Adicional</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Contar estudiantes en este grado
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM persona WHERE cod_grado = ? AND tipo_persona = 'EST'");
                        $stmt->execute([$grado['cod_grado']]);
                        $total_estudiantes = $stmt->fetch()['total'];
                        
                        $nivel = substr($grado['cod_grado'], 0, 1);
                        $nivel_nombre = [
                            '6' => 'Sexto',
                            '7' => 'Séptimo',
                            '8' => 'Octavo',
                            '9' => 'Noveno',
                            '10' => 'Décimo',
                            '11' => 'Undécimo'
                        ];
                        ?>
                        <p><strong>Código:</strong> <?php echo $grado['cod_grado']; ?></p>
                        <p><strong>Nivel:</strong> <?php echo $nivel_nombre[$nivel] ?? 'N/A'; ?></p>
                        <p><strong>Estudiantes:</strong> 
                            <span class="badge bg-success"><?php echo $total_estudiantes; ?> estudiantes</span>
                        </p>
                        
                        <?php if ($total_estudiantes > 0): ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ Advertencia:</strong> Este grado tiene estudiantes asignados. 
                            Si lo eliminas, los estudiantes perderán su referencia al grado.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=crud_grados" class="btn btn-secondary">
                ← Volver al CRUD de Grados
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 