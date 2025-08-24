<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personas - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-users me-2"></i>Sistema de Gestión
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
                    <i class="fas fa-users text-primary me-2"></i>Personas
                </h1>
                <p class="text-muted mb-0">Gestiona estudiantes, docentes y acudientes del sistema</p>
            </div>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
            <a href="index.php?action=personas&controller=create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nueva Persona
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

        <!-- Búsqueda y Filtros -->
        <div class="search-container">
            <form method="GET" class="row g-3">
                <input type="hidden" name="action" value="personas">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" name="busqueda" 
                               placeholder="Buscar por nombre, correo o ID..." 
                               value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="EST" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'EST') ? 'selected' : ''; ?>>Estudiante</option>
                        <option value="DOC" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'DOC') ? 'selected' : ''; ?>>Docente</option>
                        <option value="ADM" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'ADM') ? 'selected' : ''; ?>>Administrativo</option>
                        <option value="ACU" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'ACU') ? 'selected' : ''; ?>>Acudiente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <?php
        // Obtener personas
        try {
            $where_conditions = [];
            $params = [];
            
            // Filtro de búsqueda
            if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
                $busqueda = '%' . $_GET['busqueda'] . '%';
                $where_conditions[] = "(p.nom_user LIKE ? OR p.correo_user LIKE ? OR p.id_user LIKE ?)";
                $params[] = $busqueda;
                $params[] = $busqueda;
                $params[] = $busqueda;
            }
            
            // Filtro de tipo
            if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                $where_conditions[] = "p.tipo_persona = ?";
                $params[] = $_GET['tipo'];
            }
            
            $where_clause = '';
            if (!empty($where_conditions)) {
                $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
            }
            
            $sql = "SELECT 
                        p.id_user,
                        p.foto_persona,
                        p.nom_user,
                        p.correo_user,
                        p.telef_user,
                        p.tipo_persona,
                        p.fecha_creacion,
                                                 p.tipo_persona
                    FROM persona p
                    $where_clause
                    ORDER BY p.tipo_persona, p.nom_user
                    LIMIT 100";
            
            $personas = $db->fetchAll($sql, $params);
        } catch (Exception $e) {
            $personas = [];
            error_log("Error obteniendo personas: " . $e->getMessage());
        }
        ?>

        <?php if (empty($personas)): ?>
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No se encontraron personas</h3>
            <p class="text-muted mb-4">
                <?php if (isset($_GET['busqueda']) || isset($_GET['tipo'])): ?>
                Intenta con otros criterios de búsqueda.
                <?php else: ?>
                Aún no hay personas registradas en el sistema.
                <?php endif; ?>
            </p>
                         <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
             <a href="index.php?action=personas&controller=create" class="btn btn-primary">
                 <i class="fas fa-plus me-2"></i>Registrar Primera Persona
             </a>
             <?php endif; ?>
        </div>
        <?php else: ?>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                        <h5 class="card-title"><?php echo count(array_filter($personas, function($p) { return $p['tipo_persona'] === 'EST'; })); ?></h5>
                        <p class="card-text text-muted">Estudiantes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                        <h5 class="card-title"><?php echo count(array_filter($personas, function($p) { return $p['tipo_persona'] === 'DOC'; })); ?></h5>
                        <p class="card-text text-muted">Docentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-tie fa-2x text-warning mb-2"></i>
                        <h5 class="card-title"><?php echo count(array_filter($personas, function($p) { return $p['tipo_persona'] === 'ADM'; })); ?></h5>
                        <p class="card-text text-muted">Administrativos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-friends fa-2x text-info mb-2"></i>
                        <h5 class="card-title"><?php echo count(array_filter($personas, function($p) { return $p['tipo_persona'] === 'ACU'; })); ?></h5>
                        <p class="card-text text-muted">Acudientes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Personas -->
        <div class="row">
            <?php foreach ($personas as $persona): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card person-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <?php if (!empty($persona['foto_persona'])): ?>
                            <img src="<?php echo htmlspecialchars($persona['foto_persona']); ?>" 
                                 alt="Foto de <?php echo htmlspecialchars($persona['nom_user']); ?>" 
                                 class="person-avatar me-3">
                            <?php else: ?>
                            <div class="person-avatar me-3 d-flex align-items-center justify-content-center bg-secondary text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1"><?php echo htmlspecialchars($persona['nom_user']); ?></h6>
                                <small class="text-muted">ID: <?php echo $persona['id_user']; ?></small>
                            </div>
                            
                                                         <span class="status-badge status-active">
                                 <?php echo $persona['tipo_persona']; ?>
                             </span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>Correo:
                                    </small><br>
                                    <strong><?php echo htmlspecialchars($persona['correo_user']); ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>Teléfono:
                                    </small><br>
                                    <strong><?php echo htmlspecialchars($persona['telef_user'] ?? 'No disponible'); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user-tag me-1"></i>Tipo:
                            </small><br>
                            <strong>
                                <?php 
                                $tipos = [
                                    'EST' => 'Estudiante',
                                    'DOC' => 'Docente', 
                                    'ADM' => 'Administrativo',
                                    'ACU' => 'Acudiente'
                                ];
                                echo $tipos[$persona['tipo_persona']] ?? $persona['tipo_persona'];
                                ?>
                            </strong>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Registro: <?php echo date('d/m/Y', strtotime($persona['fecha_creacion'])); ?>
                            </small>
                            
                                                         <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
                             <div class="btn-group" role="group">
                                 <a href="index.php?action=personas&controller=edit&id=<?php echo $persona['id_user']; ?>" 
                                    class="btn btn-outline-warning btn-sm">
                                     <i class="fas fa-edit"></i>
                                 </a>
                                 <button type="button" class="btn btn-outline-danger btn-sm" 
                                         onclick="deletePerson(<?php echo $persona['id_user']; ?>)">
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
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deletePerson(personId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta persona?')) {
                // Aquí iría la lógica para eliminar la persona
                alert('Funcionalidad de eliminación en desarrollo');
            }
        }
    </script>
</body>
</html>