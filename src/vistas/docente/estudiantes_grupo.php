<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener estudiantes del grupo asignado
$estudiantes = $docenteController->getEstudiantesGrupo($_SESSION['usuario_id']);

// Obtener información del grupo asignado
$grupoAsignado = '';
try {
    $stmt = $pdo->prepare("SELECT grupo_asignado FROM persona WHERE id_user = ? AND tipo_persona = 'DOC'");
    $stmt->execute([$_SESSION['usuario_id']]);
    $docente = $stmt->fetch();
    $grupoAsignado = $docente['grupo_asignado'] ?? 'No asignado';
} catch (Exception $e) {
    error_log("Error obteniendo grupo asignado: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Grupo - Panel Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        .student-card {
            transition: transform 0.3s;
        }
        .student-card:hover {
            transform: translateY(-2px);
        }
        .group-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white">👨‍🏫 Panel Docente</h4>
                    <p class="text-white-50"><?php echo $_SESSION['usuario_nombre']; ?></p>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php?accion=dashboard_docente">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?accion=ver_estudiantes_docente">
                        <i class="fas fa-users me-2"></i> Ver Estudiantes
                    </a>
                    <a class="nav-link active" href="index.php?accion=estudiantes_grupo_docente">
                        <i class="fas fa-user-graduate me-2"></i> Mi Grupo
                    </a>
                    <a class="nav-link" href="index.php?accion=registrar_asistencia_docente">
                        <i class="fas fa-clipboard-check me-2"></i> Registrar Asistencia
                    </a>
                    <a class="nav-link" href="index.php?accion=solicitar_evento_docente">
                        <i class="fas fa-calendar-plus me-2"></i> Solicitar Evento
                    </a>
                    <a class="nav-link" href="index.php?accion=mis_peticiones_docente">
                        <i class="fas fa-list me-2"></i> Mis Peticiones
                    </a>
                    <hr class="text-white-50">
                    <a class="nav-link" href="index.php?accion=logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>👥 Mi Grupo Asignado</h2>
                <div>
                    <span class="badge bg-primary me-2">Grupo: <?php echo htmlspecialchars($grupoAsignado); ?></span>
                    <a href="index.php?accion=dashboard_docente" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Información del grupo -->
            <div class="card group-header mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">
                                <i class="fas fa-users me-2"></i>Grupo: <?php echo htmlspecialchars($grupoAsignado); ?>
                            </h4>
                            <p class="mb-0">
                                <i class="fas fa-user-graduate me-2"></i>
                                Total de estudiantes: <strong><?php echo count($estudiantes); ?></strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-grid gap-2">
                                <a href="index.php?accion=registrar_asistencia_docente" class="btn btn-light">
                                    <i class="fas fa-clipboard-check me-2"></i>Registrar Asistencia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de estudiantes del grupo -->
            <?php if (!empty($estudiantes)): ?>
            <div class="row">
                <?php foreach ($estudiantes as $estudiante): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card student-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user-graduate fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($estudiante['nom_user']); ?></h6>
                                    <small class="text-muted">ID: <?php echo $estudiante['id_user']; ?></small>
                                </div>
                            </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Grado</small>
                                    <strong><?php echo htmlspecialchars($estudiante['cod_grado'] ?? 'N/A'); ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Grupo</small>
                                    <strong><?php echo htmlspecialchars($estudiante['grupo'] ?? 'N/A'); ?></strong>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Email:</small>
                                <small><?php echo htmlspecialchars($estudiante['correo_user'] ?? 'No disponible'); ?></small>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="index.php?accion=ver_historial_estudiante_docente&id=<?php echo $estudiante['id_user']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-history me-1"></i>Ver Historial
                                </a>
                                <a href="index.php?accion=registrar_asistencia_estudiante_docente&id=<?php echo $estudiante['id_user']; ?>" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-clipboard-check me-1"></i>Registrar Asistencia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Estadísticas del grupo -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas del Grupo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary"><?php echo count($estudiantes); ?></h4>
                                    <small class="text-muted">Total Estudiantes</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo count($estudiantes); ?></h4>
                                    <small class="text-muted">Activos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="index.php?accion=registrar_asistencia_docente" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check me-1"></i>Registrar Asistencia Grupal
                                </a>
                                <a href="index.php?accion=solicitar_evento_docente" class="btn btn-success btn-sm">
                                    <i class="fas fa-calendar-plus me-1"></i>Solicitar Evento para el Grupo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <!-- Mensaje si no hay estudiantes en el grupo -->
            <div class="text-center py-5">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No tienes estudiantes asignados</h4>
                        <p class="text-muted mb-3">
                            Tu grupo asignado es: <strong><?php echo htmlspecialchars($grupoAsignado); ?></strong>
                        </p>
                        <p class="text-muted">
                            Los estudiantes aparecerán aquí una vez que sean asignados a tu grupo por el administrador.
                        </p>
                        <a href="index.php?accion=ver_estudiantes_docente" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Ver Todos los Estudiantes
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Información adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Grupo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-user-graduate text-primary me-2"></i>
                                    <strong>Grupo Asignado:</strong> <?php echo htmlspecialchars($grupoAsignado); ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-users text-success me-2"></i>
                                    <strong>Total Estudiantes:</strong> <?php echo count($estudiantes); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-clipboard-check text-warning me-2"></i>
                                    <strong>Última Asistencia:</strong> Hoy
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-info me-2"></i>
                                    <strong>Próximo Evento:</strong> No programado
                                </li>
                            </ul>
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
