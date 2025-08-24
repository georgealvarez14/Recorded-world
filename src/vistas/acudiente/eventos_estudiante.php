<?php
// Verificar que el usuario sea acudiente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
    header('Location: index.php?accion=login');
    exit;
}

$estudiante_id = $_GET['id'] ?? 0;

// Incluir el controlador de acudientes
require_once '../src/controllers/AcudienteController.php';
$acudienteController = new AcudienteController();

// Verificar que el estudiante pertenezca al acudiente
$estudiantes = $acudienteController->getEstudiantes($_SESSION['usuario_id']);
$estudiante = null;
foreach ($estudiantes as $est) {
    if ($est['id_user'] == $estudiante_id) {
        $estudiante = $est;
        break;
    }
}

if (!$estudiante) {
    $_SESSION['error'] = 'No tienes permisos para ver este estudiante';
    header('Location: index.php?accion=dashboard_acudiente');
    exit;
}

// Obtener eventos del estudiante
$eventos = $acudienteController->getEventosEstudiante($estudiante_id);

// Obtener estadísticas del estudiante
$estadisticas = $acudienteController->getEstadisticasEstudiante($estudiante_id);

// Filtrar eventos por estado
$eventos_pasados = [];
$eventos_proximos = [];
$eventos_asistidos = [];

foreach ($eventos as $evento) {
    if ($evento['fecha_llegada']) {
        $eventos_asistidos[] = $evento;
    } elseif (strtotime($evento['fecha_inicio']) < time()) {
        $eventos_pasados[] = $evento;
    } else {
        $eventos_proximos[] = $evento;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos del Estudiante - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-warning">
        <div class="container">
            <a class="navbar-brand" href="index.php?accion=dashboard_acudiente">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                </span>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Mensaje de bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-calendar"></i> Eventos de <?php echo htmlspecialchars($estudiante['nom_user']); ?></h2>
                        <p class="mb-0">Historial completo de participación en eventos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-2x mb-2"></i>
                        <h4><?php echo $estadisticas['total_eventos']; ?></h4>
                        <p class="mb-0">Total Inscritos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check fa-2x mb-2"></i>
                        <h4><?php echo $estadisticas['eventos_asistidos']; ?></h4>
                        <p class="mb-0">Asistencias</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <h4><?php echo $estadisticas['proximos_eventos']; ?></h4>
                        <p class="mb-0">Próximos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-percentage fa-2x mb-2"></i>
                        <?php 
                        $porcentaje = $estadisticas['total_eventos'] > 0 
                            ? round(($estadisticas['eventos_asistidos'] / $estadisticas['total_eventos']) * 100) 
                            : 0;
                        ?>
                        <h4><?php echo $porcentaje; ?>%</h4>
                        <p class="mb-0">Tasa Asistencia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas de eventos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="eventosTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="proximos-tab" data-bs-toggle="tab" data-bs-target="#proximos" type="button" role="tab">
                                    <i class="fas fa-calendar-alt"></i> Próximos Eventos 
                                    <span class="badge bg-warning text-dark"><?php echo count($eventos_proximos); ?></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="asistidos-tab" data-bs-toggle="tab" data-bs-target="#asistidos" type="button" role="tab">
                                    <i class="fas fa-user-check"></i> Eventos Asistidos 
                                    <span class="badge bg-success"><?php echo count($eventos_asistidos); ?></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pasados-tab" data-bs-toggle="tab" data-bs-target="#pasados" type="button" role="tab">
                                    <i class="fas fa-calendar-times"></i> Eventos Pasados 
                                    <span class="badge bg-secondary"><?php echo count($eventos_pasados); ?></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="eventosTabContent">
                            
                            <!-- Próximos eventos -->
                            <div class="tab-pane fade show active" id="proximos" role="tabpanel">
                                <?php if (empty($eventos_proximos)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay próximos eventos</h5>
                                        <p class="text-muted">El estudiante no tiene eventos programados.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th><i class="fas fa-calendar"></i> Evento</th>
                                                    <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                                    <th><i class="fas fa-clock"></i> Fecha y Hora</th>
                                                    <th><i class="fas fa-hourglass-half"></i> Duración</th>
                                                    <th><i class="fas fa-users"></i> Aforo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($eventos_proximos as $evento): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($evento['nom_evento']); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo htmlspecialchars($evento['descripcion']); ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($evento['ubicacion']); ?></td>
                                                        <td>
                                                            <strong><?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo date('H:i', strtotime($evento['hora'])); ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo $evento['duracion'] ?? 'N/A'; ?> horas</td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                <?php echo $evento['aforo_max'] ?? 'N/A'; ?> personas
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Eventos asistidos -->
                            <div class="tab-pane fade" id="asistidos" role="tabpanel">
                                <?php if (empty($eventos_asistidos)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay eventos asistidos</h5>
                                        <p class="text-muted">El estudiante aún no ha asistido a ningún evento.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-success">
                                                <tr>
                                                    <th><i class="fas fa-calendar"></i> Evento</th>
                                                    <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                                    <th><i class="fas fa-clock"></i> Fecha del Evento</th>
                                                    <th><i class="fas fa-sign-in-alt"></i> Hora de Llegada</th>
                                                    <th><i class="fas fa-sign-out-alt"></i> Hora de Salida</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($eventos_asistidos as $evento): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($evento['nom_evento']); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo htmlspecialchars($evento['descripcion']); ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($evento['ubicacion']); ?></td>
                                                        <td>
                                                            <strong><?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo date('H:i', strtotime($evento['hora'])); ?>
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">
                                                                <?php echo date('H:i', strtotime($evento['fecha_llegada'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if ($evento['hora_salida']): ?>
                                                                <span class="badge bg-info">
                                                                    <?php echo date('H:i', strtotime($evento['hora_salida'])); ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Eventos pasados -->
                            <div class="tab-pane fade" id="pasados" role="tabpanel">
                                <?php if (empty($eventos_pasados)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay eventos pasados</h5>
                                        <p class="text-muted">El estudiante no tiene eventos pasados registrados.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th><i class="fas fa-calendar"></i> Evento</th>
                                                    <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                                    <th><i class="fas fa-clock"></i> Fecha del Evento</th>
                                                    <th><i class="fas fa-user-times"></i> Estado</th>
                                                    <th><i class="fas fa-info-circle"></i> Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($eventos_pasados as $evento): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($evento['nom_evento']); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo htmlspecialchars($evento['descripcion']); ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($evento['ubicacion']); ?></td>
                                                        <td>
                                                            <strong><?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?php echo date('H:i', strtotime($evento['hora'])); ?>
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times"></i> No asistió
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                El estudiante se inscribió pero no registró asistencia
                                                            </small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="index.php?accion=ver_estudiante_acudiente&id=<?php echo $estudiante['id_user']; ?>" 
                   class="btn btn-primary">
                    <i class="fas fa-user"></i> Ver Información del Estudiante
                </a>
                <a href="index.php?accion=dashboard_acudiente" 
                   class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2024 Sistema de Eventos - Eventos del Estudiante</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 