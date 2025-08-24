<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener estudiantes
$estudiantes = $docenteController->getAllEstudiantes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Estudiantes - Panel Docente</title>
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
                    <a class="nav-link active" href="index.php?accion=ver_estudiantes_docente">
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
                <h2>👥 Lista de Estudiantes</h2>
                <div>
                    <span class="badge bg-primary me-2">Total: <?php echo count($estudiantes); ?></span>
                    <a href="index.php?accion=dashboard_docente" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filtroGrado" class="form-label">Filtrar por Grado:</label>
                            <select class="form-select" id="filtroGrado">
                                <option value="">Todos los grados</option>
                                <option value="sexto1">Sexto 1</option>
                                <option value="sexto2">Sexto 2</option>
                                <option value="septimo1">Séptimo 1</option>
                                <option value="octavo1">Octavo 1</option>
                                <option value="noveno1">Noveno 1</option>
                                <option value="decimo1">Décimo 1</option>
                                <option value="undecimo1">Undécimo 1</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtroGrupo" class="form-label">Filtrar por Grupo:</label>
                            <select class="form-select" id="filtroGrupo">
                                <option value="">Todos los grupos</option>
                                <option value="A">Grupo A</option>
                                <option value="B">Grupo B</option>
                                <option value="C">Grupo C</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="buscarEstudiante" class="form-label">Buscar:</label>
                            <input type="text" class="form-control" id="buscarEstudiante" placeholder="Nombre del estudiante...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de estudiantes -->
            <div class="row">
                <?php foreach ($estudiantes as $estudiante): ?>
                <div class="col-md-6 col-lg-4 mb-4 estudiante-item" 
                     data-grado="<?php echo htmlspecialchars($estudiante['cod_grado'] ?? ''); ?>"
                     data-grupo="<?php echo htmlspecialchars($estudiante['grupo'] ?? ''); ?>"
                     data-nombre="<?php echo htmlspecialchars($estudiante['nom_user']); ?>">
                    <div class="card student-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-graduate"></i>
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

            <!-- Mensaje si no hay estudiantes -->
            <?php if (empty($estudiantes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay estudiantes registrados</h4>
                <p class="text-muted">Los estudiantes aparecerán aquí una vez que se registren en el sistema.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Filtros de estudiantes
document.addEventListener('DOMContentLoaded', function() {
    const filtroGrado = document.getElementById('filtroGrado');
    const filtroGrupo = document.getElementById('filtroGrupo');
    const buscarEstudiante = document.getElementById('buscarEstudiante');
    const estudiantes = document.querySelectorAll('.estudiante-item');

    function aplicarFiltros() {
        const gradoSeleccionado = filtroGrado.value.toLowerCase();
        const grupoSeleccionado = filtroGrupo.value;
        const busqueda = buscarEstudiante.value.toLowerCase();

        estudiantes.forEach(estudiante => {
            const grado = estudiante.dataset.grado.toLowerCase();
            const grupo = estudiante.dataset.grupo;
            const nombre = estudiante.dataset.nombre.toLowerCase();

            const cumpleGrado = !gradoSeleccionado || grado.includes(gradoSeleccionado);
            const cumpleGrupo = !grupoSeleccionado || grupo === grupoSeleccionado;
            const cumpleBusqueda = !busqueda || nombre.includes(busqueda);

            if (cumpleGrado && cumpleGrupo && cumpleBusqueda) {
                estudiante.style.display = 'block';
            } else {
                estudiante.style.display = 'none';
            }
        });
    }

    filtroGrado.addEventListener('change', aplicarFiltros);
    filtroGrupo.addEventListener('change', aplicarFiltros);
    buscarEstudiante.addEventListener('input', aplicarFiltros);
});
</script>
</body>
</html>
