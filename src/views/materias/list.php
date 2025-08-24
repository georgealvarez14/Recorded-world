<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a class="nav-link" href="index.php?action=logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-book text-primary me-2"></i>Materias
                </h1>
                <p class="text-muted mb-0">Gestiona las categorías de materias del sistema</p>
            </div>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
            <a href="index.php?action=materias&controller=create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nueva Materia
            </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <!-- Búsqueda -->
        <div class="search-container">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="materias">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" name="busqueda" 
                               placeholder="Buscar por código o descripción..." 
                               value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <?php
        // Obtener materias
        try {
            $busqueda = isset($_GET['busqueda']) && !empty($_GET['busqueda']) 
                ? "%" . $_GET['busqueda'] . "%" 
                : "%";

            $sql = "SELECT 
                        m.cod_categoria,
                        m.descripcion
                    FROM materias m
                    WHERE m.cod_categoria LIKE ? 
                       OR m.descripcion LIKE ?
                    ORDER BY m.cod_categoria";
                    
            $materias = $db->fetchAll($sql, [$busqueda, $busqueda]);
        } catch (Exception $e) {
            $materias = [];
            error_log("Error obteniendo materias: " . $e->getMessage());
        }
        ?>

        <?php if (empty($materias)): ?>
        <div class="text-center py-5">
            <i class="fas fa-book fa-4x text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No se encontraron materias</h3>
            <p class="text-muted mb-4">
                <?php if (isset($_GET['busqueda'])): ?>
                Intenta con otros criterios de búsqueda.
                <?php else: ?>
                Aún no hay materias registradas en el sistema.
                <?php endif; ?>
            </p>
            <?php if ($_SESSION['user_type'] === 'ADM'): ?>
            <a href="index.php?action=materias&controller=create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Registrar Primera Materia
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>

        <!-- Lista de Materias -->
        <div class="row">
            <?php foreach ($materias as $materia): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-info-gradient me-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($materia['descripcion']); ?></h5>
                                <small class="text-muted">Código: <?php echo htmlspecialchars($materia['cod_categoria']); ?></small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Descripción:
                            </small><br>
                            <strong><?php echo htmlspecialchars($materia['descripcion']); ?></strong>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-primary">
                                <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($materia['cod_categoria']); ?>
                            </span>
                            
                            <?php if ($_SESSION['user_type'] === 'ADM'): ?>
                            <div class="btn-group" role="group">
                                <a href="index.php?action=materias&controller=edit&id=<?php echo urlencode($materia['cod_categoria']); ?>" 
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="deleteMateria('<?php echo htmlspecialchars($materia['cod_categoria'], ENT_QUOTES); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tabla alternativa para vista más compacta -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>Vista de Tabla
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materias as $materia): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-primary">
                                        <?php echo htmlspecialchars($materia['cod_categoria']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($materia['descripcion']); ?></td>
                                <td>
                                    <?php if ($_SESSION['user_type'] === 'ADM'): ?>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?action=materias&controller=edit&id=<?php echo urlencode($materia['cod_categoria']); ?>" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteMateria('<?php echo htmlspecialchars($materia['cod_categoria'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-muted">Sin permisos</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteMateria(codigo) {
            if (confirm('¿Estás seguro de eliminar la materia con código: ' + codigo + '?')) {
                // Aquí iría la lógica para eliminar la materia
                alert('Funcionalidad de eliminación en desarrollo');
            }
        }
    </script>
</body>
</html>