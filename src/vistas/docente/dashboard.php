<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener estadísticas
$stats = $docenteController->getEstadisticasDocente($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Docente - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-stats {
            transition: transform 0.3s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
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
                    <a class="nav-link active" href="index.php?accion=dashboard_docente">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?accion=ver_estudiantes_docente">
                        <i class="fas fa-users me-2"></i> Ver Estudiantes
                    </a>
                    <a class="nav-link" href="index.php?accion=estudiantes_grupo_docente">
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
                <h2>Dashboard del Docente</h2>
                <span class="badge bg-primary">Docente</span>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4><?php echo $stats['total_estudiantes']; ?></h4>
                            <p class="mb-0">Estudiantes en mi grupo</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                            <h4><?php echo $stats['asistencias_registradas']; ?></h4>
                            <p class="mb-0">Asistencias registradas</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <h4><?php echo $stats['total_peticiones']; ?></h4>
                            <p class="mb-0">Peticiones enviadas</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4><?php echo $stats['peticiones_pendientes']; ?></h4>
                            <p class="mb-0">Peticiones pendientes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">🚀 Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="index.php?accion=registrar_asistencia_qr_docente" class="btn btn-primary">
                                    <i class="fas fa-qrcode me-2"></i>Escanear QR
                                </a>
                                <a href="index.php?accion=registrar_asistencia_docente" class="btn btn-outline-primary">
                                    <i class="fas fa-clipboard-check me-2"></i>Registro Manual
                                </a>
                                <a href="index.php?accion=solicitar_evento_docente" class="btn btn-success">
                                    <i class="fas fa-calendar-plus me-2"></i>Solicitar Nuevo Evento
                                </a>
                                <a href="index.php?accion=ver_estudiantes_docente" class="btn btn-info">
                                    <i class="fas fa-users me-2"></i>Ver Todos los Estudiantes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">📊 Información Reciente</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-user-graduate text-primary me-2"></i>
                                    <strong>Mi grupo:</strong> 
                                    <?php 
                                    $estudiantes = $docenteController->getEstudiantesGrupo($_SESSION['usuario_id']);
                                    echo count($estudiantes) > 0 ? count($estudiantes) . ' estudiantes' : 'No asignado';
                                    ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-success me-2"></i>
                                    <strong>Última petición:</strong> 
                                    <?php 
                                    $peticiones = $docenteController->getPeticionesEvento($_SESSION['usuario_id']);
                                    echo count($peticiones) > 0 ? date('d/m/Y', strtotime($peticiones[0]['fecha_peticion'])) : 'Ninguna';
                                    ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clipboard-check text-warning me-2"></i>
                                    <strong>Última asistencia:</strong> Hoy
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peticiones recientes -->
            <?php if (count($peticiones) > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Mis Peticiones Recientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Fecha Propuesta</th>
                                    <th>Estado</th>
                                    <th>Fecha Petición</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($peticiones, 0, 5) as $peticion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($peticion['nombre_evento']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($peticion['fecha_propuesta'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $peticion['estado'] === 'PENDIENTE' ? 'warning' : ($peticion['estado'] === 'APROBADA' ? 'success' : 'danger'); ?>">
                                            <?php echo $peticion['estado']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($peticion['fecha_peticion'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="index.php?accion=mis_peticiones_docente" class="btn btn-outline-primary">Ver todas las peticiones</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
