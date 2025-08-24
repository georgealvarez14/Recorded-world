<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Materias - Administrador</title>
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
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h2>📚 CRUD Materias</h2>
                        <p class="mb-0">Gestionar todas las materias del sistema</p>
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
                        <h5 class="mb-0">🔍 Buscar Materias</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="crud_materias">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="buscar" class="form-label">Buscar:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Código o descripción de la materia...">
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
                <a href="index.php?accion=crear_materia_admin" class="btn btn-success">
                    ➕ Crear Nueva Materia
                </a>
            </div>
        </div>

        <!-- Tabla de materias -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Lista de Materias</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Eventos Asociados</th>
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
                                        $where_conditions[] = "(cod_categoria LIKE ? OR descripcion LIKE ?)";
                                        $params[] = "%$buscar%";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    $sql = "SELECT m.*, COUNT(e.cod_evento) as total_eventos FROM materias m LEFT JOIN evento e ON m.cod_categoria = e.materia";
                                    if (!empty($where_conditions)) {
                                        $sql .= " WHERE " . implode(" AND ", $where_conditions);
                                    }
                                    $sql .= " GROUP BY m.cod_categoria ORDER BY m.cod_categoria";
                                    
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $materias = $stmt->fetchAll();
                                    
                                    if (empty($materias)) {
                                        echo "<tr><td colspan='4' class='text-center text-muted'>No se encontraron materias</td></tr>";
                                    } else {
                                        foreach ($materias as $materia) {
                                            ?>
                                            <tr>
                                                <td><code><?php echo $materia['cod_categoria']; ?></code></td>
                                                <td><?php echo htmlspecialchars($materia['descripcion']); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $materia['total_eventos']; ?> eventos</span>
                                                </td>
                                                <td>
                                                    <a href="index.php?accion=editar_materia_admin&id=<?php echo $materia['cod_categoria']; ?>" 
                                                       class="btn btn-sm btn-primary">✏️ Editar</a>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="eliminarMateria('<?php echo $materia['cod_categoria']; ?>', '<?php echo htmlspecialchars($materia['descripcion']); ?>')">
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
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM materias");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">📚 Total Materias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM materias m INNER JOIN evento e ON m.cod_categoria = e.materia");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">📅 Materias con Eventos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?php 
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM materias m LEFT JOIN evento e ON m.cod_categoria = e.materia WHERE e.cod_evento IS NULL");
                            echo $stmt->fetch()['total'];
                        ?></h3>
                        <p class="mb-0">⚠️ Sin Eventos</p>
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
        function eliminarMateria(codigo, descripcion) {
            if (confirm(`¿Estás seguro de eliminar la materia "${descripcion}"?`)) {
                window.location.href = `index.php?accion=eliminar_materia_admin&id=${codigo}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 