<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener peticiones del docente
$peticiones = $docenteController->getPeticionesEvento($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Peticiones - Panel Docente</title>
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
        .petition-card {
            transition: transform 0.3s;
        }
        .petition-card:hover {
            transform: translateY(-2px);
        }
        .status-pending { background-color: #fff3cd; }
        .status-approved { background-color: #d1edff; }
        .status-rejected { background-color: #f8d7da; }
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
                    <a class="nav-link" href="index.php?accion=estudiantes_grupo_docente">
                        <i class="fas fa-user-graduate me-2"></i> Mi Grupo
                    </a>
                    <a class="nav-link" href="index.php?accion=registrar_asistencia_docente">
                        <i class="fas fa-clipboard-check me-2"></i> Registrar Asistencia
                    </a>
                    <a class="nav-link" href="index.php?accion=solicitar_evento_docente">
                        <i class="fas fa-calendar-plus me-2"></i> Solicitar Evento
                    </a>
                    <a class="nav-link active" href="index.php?accion=mis_peticiones_docente">
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
                <h2>📋 Mis Peticiones de Eventos</h2>
                <div>
                    <a href="index.php?accion=solicitar_evento_docente" class="btn btn-success me-2">
                        <i class="fas fa-plus me-2"></i>Nueva Petición
                    </a>
                    <a href="index.php?accion=dashboard_docente" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Estadísticas de peticiones -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <h4><?php echo count($peticiones); ?></h4>
                            <p class="mb-0">Total Peticiones</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4><?php echo count(array_filter($peticiones, function($p) { return $p['estado'] === 'PENDIENTE'; })); ?></h4>
                            <p class="mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h4><?php echo count(array_filter($peticiones, function($p) { return $p['estado'] === 'APROBADA'; })); ?></h4>
                            <p class="mb-0">Aprobadas</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times fa-2x mb-2"></i>
                            <h4><?php echo count(array_filter($peticiones, function($p) { return $p['estado'] === 'RECHAZADA'; })); ?></h4>
                            <p class="mb-0">Rechazadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de peticiones -->
            <?php if (!empty($peticiones)): ?>
            <div class="row">
                <?php foreach ($peticiones as $peticion): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card petition-card h-100 status-<?php echo strtolower($peticion['estado']); ?>">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?php echo htmlspecialchars($peticion['nombre_evento']); ?></h6>
                                <span class="badge bg-<?php echo $peticion['estado'] === 'PENDIENTE' ? 'warning' : ($peticion['estado'] === 'APROBADA' ? 'success' : 'danger'); ?>">
                                    <?php echo $peticion['estado']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Descripción:</small>
                                <p class="mb-0"><?php echo htmlspecialchars($peticion['descripcion']); ?></p>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Fecha Propuesta:</small>
                                    <strong><?php echo date('d/m/Y', strtotime($peticion['fecha_propuesta'])); ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Hora:</small>
                                    <strong><?php echo $peticion['hora_propuesta']; ?></strong>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Ubicación:</small>
                                <strong><?php echo htmlspecialchars($peticion['ubicacion']); ?></strong>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Materia:</small>
                                <strong><?php echo htmlspecialchars($peticion['materia']); ?></strong>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Fecha de Petición:</small>
                                <strong><?php echo date('d/m/Y H:i', strtotime($peticion['fecha_peticion'])); ?></strong>
                            </div>
                            
                            <?php if ($peticion['estado'] === 'PENDIENTE'): ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-clock me-2"></i>
                                <small>Tu petición está siendo revisada por el administrador</small>
                            </div>
                            <?php elseif ($peticion['estado'] === 'APROBADA'): ?>
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check me-2"></i>
                                <small>¡Tu petición ha sido aprobada! El evento será creado pronto</small>
                            </div>
                            <?php elseif ($peticion['estado'] === 'RECHAZADA'): ?>
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times me-2"></i>
                                <small>Tu petición ha sido rechazada. Contacta al administrador para más detalles</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Filtros -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filtroEstado" class="form-label">Filtrar por Estado:</label>
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="PENDIENTE">Pendientes</option>
                                <option value="APROBADA">Aprobadas</option>
                                <option value="RECHAZADA">Rechazadas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtroFecha" class="form-label">Filtrar por Fecha:</label>
                            <select class="form-select" id="filtroFecha">
                                <option value="">Todas las fechas</option>
                                <option value="hoy">Hoy</option>
                                <option value="semana">Esta semana</option>
                                <option value="mes">Este mes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="buscarPeticion" class="form-label">Buscar:</label>
                            <input type="text" class="form-control" id="buscarPeticion" placeholder="Nombre del evento...">
                        </div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <!-- Mensaje si no hay peticiones -->
            <div class="text-center py-5">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No tienes peticiones de eventos</h4>
                        <p class="text-muted mb-3">
                            Aún no has enviado ninguna petición de evento al administrador.
                        </p>
                        <a href="index.php?accion=solicitar_evento_docente" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Primera Petición
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Información adicional -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información sobre las Peticiones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <strong>Pendientes:</strong> Tu petición está siendo revisada
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>Aprobadas:</strong> El evento será creado pronto
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times text-danger me-2"></i>
                                    <strong>Rechazadas:</strong> Contacta al administrador
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Proceso:</strong> Revisión → Aprobación/Rechazo
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user text-info me-2"></i>
                                    <strong>Administrador:</strong> Revisa todas las peticiones
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-edit text-secondary me-2"></i>
                                    <strong>Modificación:</strong> No se pueden editar peticiones enviadas
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
<script>
// Filtros de peticiones
document.addEventListener('DOMContentLoaded', function() {
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroFecha = document.getElementById('filtroFecha');
    const buscarPeticion = document.getElementById('buscarPeticion');
    const peticiones = document.querySelectorAll('.petition-card');

    function aplicarFiltros() {
        const estadoSeleccionado = filtroEstado.value;
        const fechaSeleccionada = filtroFecha.value;
        const busqueda = buscarPeticion.value.toLowerCase();

        peticiones.forEach(peticion => {
            const estado = peticion.querySelector('.badge').textContent.trim();
            const nombre = peticion.querySelector('.card-header h6').textContent.toLowerCase();
            const fecha = peticion.querySelector('.card-body strong').textContent;

            const cumpleEstado = !estadoSeleccionado || estado === estadoSeleccionado;
            const cumpleBusqueda = !busqueda || nombre.includes(busqueda);
            
            // Filtro de fecha (simplificado)
            let cumpleFecha = true;
            if (fechaSeleccionada === 'hoy') {
                const hoy = new Date().toLocaleDateString('es-ES');
                cumpleFecha = fecha.includes(hoy.split('/')[0]); // Solo día
            }

            if (cumpleEstado && cumpleBusqueda && cumpleFecha) {
                peticion.style.display = 'block';
            } else {
                peticion.style.display = 'none';
            }
        });
    }

    if (filtroEstado) filtroEstado.addEventListener('change', aplicarFiltros);
    if (filtroFecha) filtroFecha.addEventListener('change', aplicarFiltros);
    if (buscarPeticion) buscarPeticion.addEventListener('input', aplicarFiltros);
});
</script>
</body>
</html>
