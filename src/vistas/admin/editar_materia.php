<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Materia - Administrador</title>
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
                        <h2>✏️ Editar Materia</h2>
                        <p class="mb-0">Modificar información de la materia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📝 Información de la Materia</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_materia_admin">
                            <input type="hidden" name="codigo" value="<?php echo $materia['cod_categoria']; ?>">
                            
                            <div class="mb-3">
                                <label for="codigo_display" class="form-label">Código de la Materia</label>
                                <input type="text" class="form-control" id="codigo_display" 
                                       value="<?php echo $materia['cod_categoria']; ?>" readonly>
                                <div class="form-text">El código no se puede modificar</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                       value="<?php echo htmlspecialchars($materia['descripcion']); ?>" 
                                       required placeholder="Ej: Matemáticas, Ciencias, Español">
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=crud_materias" class="btn btn-secondary me-md-2">
                                            ❌ Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            💾 Actualizar Materia
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
                        // Contar eventos asociados
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM evento WHERE materia = ?");
                        $stmt->execute([$materia['cod_categoria']]);
                        $total_eventos = $stmt->fetch()['total'];
                        ?>
                        <p><strong>Código:</strong> <?php echo $materia['cod_categoria']; ?></p>
                        <p><strong>Eventos asociados:</strong> 
                            <span class="badge bg-info"><?php echo $total_eventos; ?> eventos</span>
                        </p>
                        
                        <?php if ($total_eventos > 0): ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ Advertencia:</strong> Esta materia tiene eventos asociados. 
                            Si la eliminas, los eventos perderán su referencia a la materia.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=crud_materias" class="btn btn-secondary">
                ← Volver al CRUD de Materias
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 