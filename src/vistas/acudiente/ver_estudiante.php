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

// Obtener estadísticas del estudiante
$estadisticas = $acudienteController->getEstadisticasEstudiante($estudiante_id);

// Obtener eventos del estudiante
$eventos = $acudienteController->getEventosEstudiante($estudiante_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Estudiante - Sistema de Eventos</title>
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
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-user-graduate"></i> Información del Estudiante</h2>
                        <p class="mb-0">Detalles completos de <?php echo htmlspecialchars($estudiante['nom_user']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas del estudiante -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-3x mb-3"></i>
                        <h3><?php echo $estadisticas['total_eventos']; ?></h3>
                        <p class="mb-0">Total Eventos Inscritos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check fa-3x mb-3"></i>
                        <h3><?php echo $estadisticas['eventos_asistidos']; ?></h3>
                        <p class="mb-0">Eventos Asistidos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                        <h3><?php echo $estadisticas['proximos_eventos']; ?></h3>
                        <p class="mb-0">Próximos Eventos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del estudiante -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> Datos Personales
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td><?php echo htmlspecialchars($estudiante['nom_user']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($estudiante['correo_user']); ?>">
                                        <?php echo htmlspecialchars($estudiante['correo_user']); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>
                                    <a href="tel:<?php echo $estudiante['telef_user']; ?>">
                                        <?php echo $estudiante['telef_user']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Grado:</strong></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($estudiante['grado_nombre'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Grupo:</strong></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo $estudiante['grupo'] ?? 'N/A'; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ciudad:</strong></td>
                                <td><?php echo htmlspecialchars($estudiante['ciudad_nombre'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de registro:</strong></td>
                                <td><?php echo $estudiante['fecha_creacion'] ?? 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar"></i> Estadísticas de Participación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary"><?php echo $estadisticas['total_eventos']; ?></h4>
                                    <small class="text-muted">Total Inscripciones</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-success"><?php echo $estadisticas['eventos_asistidos']; ?></h4>
                                    <small class="text-muted">Asistencias</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning"><?php echo $estadisticas['proximos_eventos']; ?></h4>
                                    <small class="text-muted">Próximos Eventos</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <?php 
                                    $porcentaje = $estadisticas['total_eventos'] > 0 
                                        ? round(($estadisticas['eventos_asistidos'] / $estadisticas['total_eventos']) * 100) 
                                        : 0;
                                    ?>
                                    <h4 class="text-info"><?php echo $porcentaje; ?>%</h4>
                                    <small class="text-muted">Tasa de Asistencia</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="index.php?accion=eventos_estudiante_acudiente&id=<?php echo $estudiante['id_user']; ?>" 
                               class="btn btn-success w-100">
                                <i class="fas fa-calendar"></i> Ver Todos los Eventos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos recientes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar"></i> Eventos Recientes (Últimos 5)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($eventos)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay eventos registrados</h5>
                                <p class="text-muted">El estudiante no se ha inscrito en ningún evento aún.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-info">
                                        <tr>
                                            <th><i class="fas fa-calendar"></i> Evento</th>
                                            <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                                            <th><i class="fas fa-clock"></i> Fecha</th>
                                            <th><i class="fas fa-user-check"></i> Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $eventos_recientes = array_slice($eventos, 0, 5);
                                        foreach ($eventos_recientes as $evento): 
                                        ?>
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
                                                    <?php echo date('d/m/Y H:i', strtotime($evento['fecha_inicio'])); ?>
                                                </td>
                                                <td>
                                                    <?php if ($evento['fecha_llegada']): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> Asistió
                                                        </span>
                                                    <?php elseif (strtotime($evento['fecha_inicio']) < time()): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times"></i> No asistió
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock"></i> Pendiente
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($eventos) > 5): ?>
                                <div class="text-center mt-3">
                                    <a href="index.php?accion=eventos_estudiante_acudiente&id=<?php echo $estudiante['id_user']; ?>" 
                                       class="btn btn-info">
                                        <i class="fas fa-list"></i> Ver Todos los Eventos
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2024 Sistema de Eventos - Información del Estudiante</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 