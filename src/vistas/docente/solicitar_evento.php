<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Procesar formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'fecha' => $_POST['fecha'] ?? '',
        'hora' => $_POST['hora'] ?? '',
        'ubicacion' => $_POST['ubicacion'] ?? '',
        'materia' => $_POST['materia'] ?? ''
    ];
    
    $resultado = $docenteController->enviarPeticionEvento($_SESSION['usuario_id'], $datos);
    
    if ($resultado['success']) {
        $_SESSION['success'] = $resultado['message'];
    } else {
        $_SESSION['error'] = $resultado['message'];
    }
    
    header('Location: index.php?accion=solicitar_evento_docente');
    exit;
}

// Obtener materias disponibles
$materias = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM materias ORDER BY descripcion");
    $stmt->execute();
    $materias = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error obteniendo materias: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Evento - Panel Docente</title>
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
        .form-card {
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 15px;
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
                    <a class="nav-link" href="index.php?accion=registrar_asistencia_docente">
                        <i class="fas fa-clipboard-check me-2"></i> Registrar Asistencia
                    </a>
                    <a class="nav-link active" href="index.php?accion=solicitar_evento_docente">
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
                <h2>📅 Solicitar Nuevo Evento</h2>
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

            <!-- Formulario de solicitud -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card form-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-plus me-2"></i>Formulario de Solicitud de Evento
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label">
                                            <i class="fas fa-tag me-1"></i>Nombre del Evento *
                                        </label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               required placeholder="Ej: Taller de Matemáticas">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="materia" class="form-label">
                                            <i class="fas fa-book me-1"></i>Materia *
                                        </label>
                                        <select class="form-select" id="materia" name="materia" required>
                                            <option value="">Seleccionar materia</option>
                                            <?php foreach ($materias as $materia): ?>
                                            <option value="<?php echo htmlspecialchars($materia['cod_categoria']); ?>">
                                                <?php echo htmlspecialchars($materia['descripcion']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Descripción del Evento *
                                    </label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="4" required placeholder="Describe el evento, objetivos, actividades..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>Fecha Propuesta *
                                        </label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" 
                                               required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="hora" class="form-label">
                                            <i class="fas fa-clock me-1"></i>Hora Propuesta *
                                        </label>
                                        <input type="time" class="form-control" id="hora" name="hora" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="ubicacion" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Ubicación Propuesta *
                                    </label>
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                                           required placeholder="Ej: Aula 101, Auditorio, Patio...">
                                </div>

                                <!-- Información adicional -->
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Información Importante:</h6>
                                    <ul class="mb-0">
                                        <li>Tu solicitud será revisada por el administrador</li>
                                        <li>Recibirás una notificación cuando se apruebe o rechace</li>
                                        <li>Puedes ver el estado de tus solicitudes en "Mis Peticiones"</li>
                                        <li>Los campos marcados con * son obligatorios</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="index.php?accion=dashboard_docente" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Enviar Solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peticiones recientes -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>Mis Peticiones Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $peticiones = $docenteController->getPeticionesEvento($_SESSION['usuario_id']);
                            if (count($peticiones) > 0): 
                            ?>
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
                                        <?php foreach (array_slice($peticiones, 0, 3) as $peticion): ?>
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
                                <a href="index.php?accion=mis_peticiones_docente" class="btn btn-outline-primary">
                                    Ver todas las peticiones
                                </a>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No tienes peticiones de eventos aún</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fechaInput = document.getElementById('fecha');
    
    // Establecer fecha mínima como hoy
    const today = new Date().toISOString().split('T')[0];
    fechaInput.min = today;
    
    form.addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const fecha = document.getElementById('fecha').value;
        const hora = document.getElementById('hora').value;
        const ubicacion = document.getElementById('ubicacion').value.trim();
        const materia = document.getElementById('materia').value;
        
        if (!nombre || !descripcion || !fecha || !hora || !ubicacion || !materia) {
            e.preventDefault();
            alert('Por favor, completa todos los campos obligatorios.');
            return false;
        }
        
        // Confirmar envío
        if (!confirm('¿Estás seguro de que quieres enviar esta solicitud de evento?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
</body>
</html>
