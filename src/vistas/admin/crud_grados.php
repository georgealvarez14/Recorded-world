<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Grados - Administrador</title>
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
                        <h2>🎓 CRUD Grados</h2>
                        <p class="mb-0">Gestionar todos los grados del sistema</p>
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

        <!-- Buscador y filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🔍 Buscar y Filtrar</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="crud_grados">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="buscar" class="form-label">Buscar:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Código o descripción del grado...">
                                </div>
                                <div class="col-md-4">
                                    <label for="nivel" class="form-label">Nivel:</label>
                                    <select class="form-select" id="nivel" name="nivel">
                                        <option value="">Todos</option>
                                        <option value="6" <?php echo ($_GET['nivel'] ?? '') === '6' ? 'selected' : ''; ?>>Sexto</option>
                                        <option value="7" <?php echo ($_GET['nivel'] ?? '') === '7' ? 'selected' : ''; ?>>Séptimo</option>
                                        <option value="8" <?php echo ($_GET['nivel'] ?? '') === '8' ? 'selected' : ''; ?>>Octavo</option>
                                        <option value="9" <?php echo ($_GET['nivel'] ?? '') === '9' ? 'selected' : ''; ?>>Noveno</option>
                                        <option value="10" <?php echo ($_GET['nivel'] ?? '') === '10' ? 'selected' : ''; ?>>Décimo</option>
                                        <option value="11" <?php echo ($_GET['nivel'] ?? '') === '11' ? 'selected' : ''; ?>>Undécimo</option>
                                    </select>
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
                <a href="index.php?accion=crear_grado_admin" class="btn btn-success">
                    ➕ Crear Nuevo Grado
                </a>
            </div>
        </div>

        <!-- Tabla de grados -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Lista de Grados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Nivel</th>
                                        <th>Estudiantes</th>
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
                                        $where_conditions[] = "(cod_grado LIKE ? OR descripcion LIKE ?)";
                                        $params[] = "%$buscar%";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    if (!empty($_GET['nivel'])) {
                                        $where_conditions[] = "cod_grado LIKE ?";
                                        $params[] = $_GET['nivel'] . '%';
                                    }
                                    
                                    $sql = "SELECT g.*, COUNT(p.id_user) as total_estudiantes FROM grado g LEFT JOIN persona p ON g.cod_grado = p.cod_grado AND p.tipo_persona = 'EST'";
                                    if (!empty($where_conditions)) {
                                        $sql .= " WHERE " . implode(" AND ", $where_conditions);
                                    }
                                    $sql .= " GROUP BY g.cod_grado ORDER BY g.cod_grado";
                                    
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $grados = $stmt->fetchAll();
                                    
                                    if (empty($grados)) {
                                        echo "<tr><td colspan='5' class='text-center text-muted'>No se encontraron grados</td></tr>";
                                    } else {
                                        foreach ($grados as $grado) {
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
                                            <tr>
                                                <td><code><?php echo $grado['cod_grado']; ?></code></td>
                                                <td><?php echo htmlspecialchars($grado['descripcion']); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $nivel_nombre[$nivel] ?? 'N/A'; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo $grado['total_estudiantes']; ?> estudiantes</span>
                                                </td>
                                                <td>
                                                    <a href="index.php?accion=editar_grado_admin&id=<?php echo $grado['cod_grado']; ?>" 
                                                       class="btn btn-sm btn-primary">✏️ Editar</a>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="eliminarGrado(<?php echo $grado['cod_grado']; ?>, '<?php echo htmlspecialchars($grado['descripcion']); ?>')">
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
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM grado");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">🎓 Total Grados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM grado g INNER JOIN persona p ON g.cod_grado = p.cod_grado WHERE p.tipo_persona = 'EST'");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">👨‍🎓 Con Estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM grado g LEFT JOIN persona p ON g.cod_grado = p.cod_grado AND p.tipo_persona = 'EST' WHERE p.id_user IS NULL");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">⚠️ Sin Estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(DISTINCT SUBSTRING(cod_grado, 1, 1)) as total FROM grado");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">📚 Niveles</p>
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
        function eliminarGrado(codigo, descripcion) {
            if (confirm(`¿Estás seguro de eliminar el grado "${descripcion}"?`)) {
                window.location.href = `index.php?accion=eliminar_grado_admin&id=${codigo}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 