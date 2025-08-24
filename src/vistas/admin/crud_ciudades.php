<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Ciudades - Administrador</title>
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
                        <h2>🏙️ CRUD Ciudades</h2>
                        <p class="mb-0">Gestionar todas las ciudades del sistema</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Buscador -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🔍 Buscar Ciudades</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="crud_ciudades">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="buscar" class="form-label">Buscar:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Código o nombre de la ciudad...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">🔍 Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón crear -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="index.php?accion=crear_ciudad_admin" class="btn btn-success">
                    ➕ Crear Nueva Ciudad
                </a>
            </div>
        </div>

        <!-- Tabla de ciudades -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Lista de Ciudades</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Personas Registradas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Construir consulta con filtros
                                    $where_conditions = [];
                                    $params = [];
                                    
                                    if (!empty($_GET['buscar'])) {
                                        $buscar = $_GET['buscar'];
                                        $where_conditions[] = "(cod_ciudad LIKE ? OR descripcion LIKE ?)";
                                        $params[] = "%$buscar%";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    $sql = "SELECT c.*, COUNT(p.id_user) as total_personas FROM ciudad c LEFT JOIN persona p ON c.cod_ciudad = p.ciudad";
                                    if (!empty($where_conditions)) {
                                        $sql .= " WHERE " . implode(" AND ", $where_conditions);
                                    }
                                    $sql .= " GROUP BY c.cod_ciudad ORDER BY c.descripcion";
                                    
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $ciudades = $stmt->fetchAll();
                                    
                                    if (empty($ciudades)) {
                                        echo "<tr><td colspan='4' class='text-center text-muted'>No se encontraron ciudades</td></tr>";
                                    } else {
                                        foreach ($ciudades as $ciudad) {
                                            ?>
                                            <tr>
                                                <td><code><?php echo $ciudad['cod_ciudad']; ?></code></td>
                                                <td><?php echo htmlspecialchars($ciudad['descripcion']); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $ciudad['total_personas']; ?> personas</span>
                                                </td>
                                                <td>
                                                    <a href="index.php?accion=editar_ciudad_admin&id=<?php echo $ciudad['cod_ciudad']; ?>" 
                                                       class="btn btn-sm btn-primary">✏️ Editar</a>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="eliminarCiudad('<?php echo $ciudad['cod_ciudad']; ?>', '<?php echo htmlspecialchars($ciudad['descripcion']); ?>')">
                                                        🗑️ Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM ciudad");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">🏙️ Total Ciudades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM ciudad c INNER JOIN persona p ON c.cod_ciudad = p.ciudad");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">👥 Con Personas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM ciudad c LEFT JOIN persona p ON c.cod_ciudad = p.ciudad WHERE p.id_user IS NULL");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">⚠️ Sin Personas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=inicio" class="btn btn-secondary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        function eliminarCiudad(codigo, nombre) {
            if (confirm(`¿Estás seguro de eliminar la ciudad "${nombre}"?`)) {
                window.location.href = `index.php?accion=eliminar_ciudad_admin&id=${codigo}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 