<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Acudiente - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .student-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .student-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, var(--color-primario), #0056b3);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .event-badge {
            background: var(--color-exito);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin: 2px;
            display: inline-block;
        }
        .attendance-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .attendance-present {
            background: var(--color-exito);
        }
        .attendance-absent {
            background: var(--color-peligro);
        }
        .attendance-pending {
            background: var(--color-advertencia);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-user-friends me-2"></i>Panel Acudiente
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?action=acudiente-estudiantes">
                    <i class="fas fa-users me-1"></i>Mis Estudiantes
                </a>
                <a class="nav-link" href="index.php?action=acudiente-eventos">
                    <i class="fas fa-calendar me-1"></i>Eventos
                </a>
                <a class="nav-link" href="index.php?action=acudiente-perfil">
                    <i class="fas fa-user me-1"></i>Mi Perfil
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
        <!-- Welcome Section -->
        <div class="welcome-section mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2 text-white">
                        <i class="fas fa-user-friends me-2"></i>
                        Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </h1>
                    <p class="mb-0 text-white opacity-75">
                        Panel de control para acudientes - Seguimiento de estudiantes
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3 text-white">
                            <h4 class="mb-0"><?php echo date('d'); ?></h4>
                            <small><?php echo date('M Y'); ?></small>
                        </div>
                        <i class="fas fa-user-friends fa-3x text-white opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $resumen['total_estudiantes']; ?></h3>
                            <small>Estudiantes</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $resumen['total_eventos']; ?></h3>
                            <small>Total Eventos</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $resumen['eventos_proximos']; ?></h3>
                            <small>Próximos Eventos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Section -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2 text-primary"></i>
                            Mis Estudiantes
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($resumen['estudiantes'])): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted mb-3">No hay estudiantes registrados</h5>
                            <p class="text-muted">Contacta al administrador para registrar estudiantes a tu cargo.</p>
                        </div>
                        <?php else: ?>
                        
                        <div class="row">
                            <?php foreach ($resumen['estudiantes'] as $item): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="student-card">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($item['estudiante']['nom_user']); ?></h5>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($item['estudiante']['grado_nombre'] ?? 'Sin grado'); ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="text-primary">
                                                <h6 class="mb-0"><?php echo $item['estadisticas']['total_eventos']; ?></h6>
                                                <small>Eventos</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-success">
                                                <h6 class="mb-0"><?php echo $item['estadisticas']['eventos_asistidos']; ?></h6>
                                                <small>Asistidos</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-warning">
                                                <h6 class="mb-0"><?php echo $item['estadisticas']['proximos_eventos']; ?></h6>
                                                <small>Próximos</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="index.php?action=acudiente-estudiante&id=<?php echo $item['estudiante']['id_user']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </a>
                                        <a href="index.php?action=acudiente-eventos&estudiante=<?php echo $item['estudiante']['id_user']; ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-calendar me-1"></i>Eventos
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2 text-warning"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="index.php?action=acudiente-estudiantes" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-users me-2"></i>Ver Todos los Estudiantes
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?action=acudiente-eventos" class="btn btn-outline-success w-100">
                                    <i class="fas fa-calendar me-2"></i>Ver Todos los Eventos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?action=acudiente-perfil" class="btn btn-outline-info w-100">
                                    <i class="fas fa-user-edit me-2"></i>Editar Mi Perfil
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?action=eventos" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-search me-2"></i>Buscar Eventos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 