<?php
// Verificar que el usuario sea acudiente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir el controlador de acudientes
require_once '../src/controllers/AcudienteController.php';
$acudienteController = new AcudienteController();

// Obtener información del acudiente
$acudiente = $acudienteController->getInformacionAcudiente($_SESSION['usuario_id']);

// Obtener resumen de estudiantes
$resumen = $acudienteController->getResumenAcudiente($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Acudiente - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-warning">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-users"></i> Sistema de Eventos - Acudiente
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
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-users"></i> Panel de Acudiente</h2>
                        <p class="mb-0">Gestiona la información de tus estudiantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                        <h3><?php echo $resumen['total_estudiantes']; ?></h3>
                        <p class="mb-0">Estudiantes a Cargo</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-3x mb-3"></i>
                        <h3><?php echo $resumen['total_eventos']; ?></h3>
                        <p class="mb-0">Total Eventos Inscritos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                        <h3><?php echo $resumen['eventos_proximos']; ?></h3>
                        <p class="mb-0">Próximos Eventos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de estudiantes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-user-graduate"></i> Mis Estudiantes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($resumen['estudiantes'])): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No tienes estudiantes asignados</h5>
                                <p class="text-muted">Contacta con la administración para que te asignen estudiantes.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-warning">
                                        <tr>
                                            <th><i class="fas fa-user"></i> Estudiante</th>
                                            <th><i class="fas fa-graduation-cap"></i> Grado</th>
                                            <th><i class="fas fa-envelope"></i> Email</th>
                                            <th><i class="fas fa-phone"></i> Teléfono</th>
                                            <th><i class="fas fa-calendar-check"></i> Eventos</th>
                                            <th><i class="fas fa-cogs"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resumen['estudiantes'] as $item): ?>
                                            <?php $estudiante = $item['estudiante']; ?>
                                            <?php $estadisticas = $item['estadisticas']; ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($estudiante['nom_user']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo htmlspecialchars($estudiante['grado_nombre'] ?? 'N/A'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($estudiante['correo_user']); ?>">
                                                        <?php echo htmlspecialchars($estudiante['correo_user']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="tel:<?php echo $estudiante['telef_user']; ?>">
                                                        <?php echo $estudiante['telef_user']; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?php echo $estadisticas['total_eventos']; ?> inscritos
                                                    </span>
                                                    <?php if ($estadisticas['proximos_eventos'] > 0): ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <?php echo $estadisticas['proximos_eventos']; ?> próximos
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="index.php?accion=ver_estudiante_acudiente&id=<?php echo $estudiante['id_user']; ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Ver información completa">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="index.php?accion=eventos_estudiante_acudiente&id=<?php echo $estudiante['id_user']; ?>" 
                                                           class="btn btn-sm btn-outline-success" 
                                                           title="Ver eventos del estudiante">
                                                            <i class="fas fa-calendar"></i>
                                                        </a>
                                                    </div>
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

        <!-- Información del acudiente -->
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> Mi Información
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($acudiente['nom_user'] ?? $_SESSION['usuario_nombre']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($acudiente['correo_user'] ?? $_SESSION['usuario_email']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo $acudiente['telef_user'] ?? 'No registrado'; ?></p>
                        <p><strong>Fecha de registro:</strong> <?php echo $acudiente['fecha_creacion'] ?? 'No disponible'; ?></p>
                        
                        <a href="index.php?accion=editar_perfil_acudiente" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Información del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Rol:</strong> Acudiente</p>
                        <p><strong>Permisos:</strong> Ver información de estudiantes a cargo</p>
                        <p><strong>Funcionalidades:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Ver lista de estudiantes</li>
                            <li><i class="fas fa-check text-success"></i> Ver eventos de estudiantes</li>
                            <li><i class="fas fa-check text-success"></i> Ver estadísticas de participación</li>
                            <li><i class="fas fa-check text-success"></i> Editar información personal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2024 Sistema de Eventos - Panel de Acudiente</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 