<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ciudad - Administrador</title>
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
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <h2>➕ Crear Nueva Ciudad</h2>
                        <p class="mb-0">Agregar una nueva ciudad al sistema</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📝 Información de la Ciudad</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=guardar_ciudad_admin">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código de la Ciudad *</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" 
                                       maxlength="2" required placeholder="Ej: BO, ME, CA">
                                <div class="form-text">Máximo 2 caracteres (ej: BO para Bogotá)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Nombre de la Ciudad *</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                       required placeholder="Ej: Bogotá, Medellín, Cali">
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=crud_ciudades" class="btn btn-secondary me-md-2">
                                            ❌ Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            💾 Guardar Ciudad
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ciudades existentes -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Ciudades Existentes</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM ciudad ORDER BY descripcion");
                        $ciudades = $stmt->fetchAll();
                        
                        if (empty($ciudades)) {
                            echo '<p class="text-muted">No hay ciudades registradas.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-sm">';
                            echo '<thead><tr><th>Código</th><th>Nombre</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($ciudades as $ciudad) {
                                echo '<tr>';
                                echo '<td><code>' . $ciudad['cod_ciudad'] . '</code></td>';
                                echo '<td>' . htmlspecialchars($ciudad['descripcion']) . '</td>';
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
            <a href="index.php?accion=crud_ciudades" class="btn btn-secondary">
                ← Volver al CRUD de Ciudades
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 