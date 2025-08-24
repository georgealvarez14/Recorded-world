<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener grupos disponibles y estudiantes
$grupos = $docenteController->getGruposDisponibles();
$estudiantes = $docenteController->getAllEstudiantes();

// Si se selecciona un grupo específico
$grupoSeleccionado = $_GET['grupo'] ?? '';
if ($grupoSeleccionado) {
    $estudiantes = $docenteController->getEstudiantesPorGrupo($grupoSeleccionado);
}

// Obtener eventos disponibles
$eventos = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM evento ORDER BY fecha_inicio DESC");
    $stmt->execute();
    $eventos = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error obteniendo eventos: " . $e->getMessage());
}

// Procesar registro de asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = $_POST['evento_id'] ?? '';
    $estudiante_id = $_POST['estudiante_id'] ?? '';
    $asistio = isset($_POST['asistio']) ? true : false;
    
    if ($evento_id && $estudiante_id) {
        $resultado = $docenteController->registrarAsistencia($evento_id, $estudiante_id, $_SESSION['usuario_id'], $asistio);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
    } else {
        $_SESSION['error'] = 'Por favor, selecciona un evento y un estudiante';
    }
    
    header('Location: index.php?accion=registrar_asistencia_docente');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia - Panel Docente</title>
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
        .attendance-card {
            transition: transform 0.3s;
        }
        .attendance-card:hover {
            transform: translateY(-2px);
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
                    <a class="nav-link" href="index.php?accion=estudiantes_grupo_docente">
                        <i class="fas fa-user-graduate me-2"></i> Mi Grupo
                    </a>
                    <a class="nav-link active" href="index.php?accion=registrar_asistencia_docente">
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
                <h2>📋 Registrar Asistencia</h2>
                <a href="index.php?accion=dashboard_docente" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <!-- Mensajes de éxito/error -->
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

            <!-- Información y filtros -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Registro de Asistencia - Todos los Grupos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Total de estudiantes:</strong> <?php echo count($estudiantes); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Grupos disponibles:</strong> <?php echo count($grupos); ?></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="index.php?accion=estudiantes_grupo_docente" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Ver Mi Grupo
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtro por grupo -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="filtroGrupo" class="form-label">Filtrar por Grupo:</label>
                            <select class="form-select" id="filtroGrupo" onchange="filtrarPorGrupo(this.value)">
                                <option value="">Todos los grupos</option>
                                <?php foreach ($grupos as $grupo): ?>
                                <option value="<?php echo htmlspecialchars($grupo); ?>" 
                                        <?php echo $grupoSeleccionado === $grupo ? 'selected' : ''; ?>>
                                    Grupo <?php echo htmlspecialchars($grupo); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="buscarEstudiante" class="form-label">Buscar Estudiante:</label>
                            <input type="text" class="form-control" id="buscarEstudiante" placeholder="Nombre del estudiante...">
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($estudiantes)): ?>
            <!-- Formulario de registro de asistencia -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>Registro Individual
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="evento_id" class="form-label">Evento:</label>
                                    <select class="form-select" id="evento_id" name="evento_id" required>
                                        <option value="">Seleccionar evento</option>
                                        <?php foreach ($eventos as $evento): ?>
                                        <option value="<?php echo $evento['cod_evento']; ?>">
                                            <?php echo htmlspecialchars($evento['nom_evento']); ?> 
                                            (<?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estudiante_id" class="form-label">Estudiante:</label>
                                    <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                                        <option value="">Seleccionar estudiante</option>
                                        <?php foreach ($estudiantes as $estudiante): ?>
                                        <option value="<?php echo $estudiante['id_user']; ?>">
                                            <?php echo htmlspecialchars($estudiante['nom_user']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="asistio" name="asistio" checked>
                                        <label class="form-check-label" for="asistio">
                                            Asistió al evento
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Registrar Asistencia
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Lista de Estudiantes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($estudiantes as $estudiante): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card attendance-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($estudiante['nom_user']); ?></h6>
                                                    <small class="text-muted">ID: <?php echo $estudiante['id_user']; ?></small>
                                                </div>
                                            </div>
                                            
                                            <div class="row text-center mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Grado</small>
                                                    <strong><?php echo htmlspecialchars($estudiante['cod_grado'] ?? 'N/A'); ?></strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Grupo</small>
                                                    <strong><?php echo htmlspecialchars($estudiante['grupo'] ?? 'N/A'); ?></strong>
                                                </div>
                                            </div>
                                            
                                            <div class="d-grid gap-1">
                                                <a href="index.php?accion=ver_historial_estudiante_docente&id=<?php echo $estudiante['id_user']; ?>" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-history me-1"></i>Ver Historial
                                                </a>
                                                <a href="index.php?accion=registrar_asistencia_estudiante_docente&id=<?php echo $estudiante['id_user']; ?>" 
                                                   class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-clipboard-check me-1"></i>Registrar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información Importante
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Puedes registrar asistencia de cualquier grupo con el que tengas clase
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            Selecciona el evento para el cual registrarás la asistencia
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-filter text-info me-2"></i>
                                            Usa los filtros para encontrar estudiantes específicos
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-user text-info me-2"></i>
                                            Puedes registrar asistencia individual o ver el historial completo
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-history text-warning me-2"></i>
                                            Los registros se pueden modificar posteriormente
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-users text-secondary me-2"></i>
                                            Filtra por grupo para facilitar el registro masivo
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <!-- Mensaje si no hay estudiantes -->
            <div class="text-center py-5">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay estudiantes disponibles</h4>
                        <p class="text-muted mb-3">
                            No se encontraron estudiantes para registrar asistencia.
                        </p>
                        <a href="index.php?accion=ver_estudiantes_docente" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Ver Todos los Estudiantes
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const evento = document.getElementById('evento_id').value;
        const estudiante = document.getElementById('estudiante_id').value;
        
        if (!evento || !estudiante) {
            e.preventDefault();
            alert('Por favor, selecciona un evento y un estudiante.');
            return false;
        }
        
        if (!confirm('¿Estás seguro de que quieres registrar esta asistencia?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Filtro de estudiantes
    const buscarEstudiante = document.getElementById('buscarEstudiante');
    const estudiantes = document.querySelectorAll('.attendance-card');
    
    if (buscarEstudiante) {
        buscarEstudiante.addEventListener('input', function() {
            const busqueda = this.value.toLowerCase();
            
            estudiantes.forEach(estudiante => {
                const nombre = estudiante.querySelector('h6').textContent.toLowerCase();
                
                if (nombre.includes(busqueda)) {
                    estudiante.style.display = 'block';
                } else {
                    estudiante.style.display = 'none';
                }
            });
        });
    }
});

// Función para filtrar por grupo
function filtrarPorGrupo(grupo) {
    if (grupo) {
        window.location.href = 'index.php?accion=registrar_asistencia_docente&grupo=' + encodeURIComponent(grupo);
    } else {
        window.location.href = 'index.php?accion=registrar_asistencia_docente';
    }
}
</script>
</body>
</html>
