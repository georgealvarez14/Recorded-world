<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Grado - Administrador</title>
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
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h2>➕ Crear Nuevo Grado</h2>
                        <p class="mb-0">Agregar un nuevo grado al sistema</p>
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
                        <form method="POST" action="index.php?accion=guardar_grado_admin">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código del Grado *</label>
                                <input type="number" class="form-control" id="codigo" name="codigo" 
                                       min="61" max="115" required placeholder="Ej: 61, 72, 83">
                                <div class="form-text">Formato: [Nivel][Grupo] (ej: 61 = Sexto 1, 72 = Séptimo 2)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                       required placeholder="Ej: Sexto 1, Séptimo 2, Octavo 3">
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=crud_grados" class="btn btn-secondary me-md-2">
                                            ❌ Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            💾 Guardar Grado
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grados existentes -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Grados Existentes</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM grado ORDER BY cod_grado");
                        $grados = $stmt->fetchAll();
                        
                        if (empty($grados)) {
                            echo '<p class="text-muted">No hay grados registrados.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-sm">';
                            echo '<thead><tr><th>Código</th><th>Descripción</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($grados as $grado) {
                                echo '<tr>';
                                echo '<td><code>' . $grado['cod_grado'] . '</code></td>';
                                echo '<td>' . htmlspecialchars($grado['descripcion']) . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        }
                        ?>
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