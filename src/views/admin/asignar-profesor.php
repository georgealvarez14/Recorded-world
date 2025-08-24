<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Profesores - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .assignment-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .section-title {
            color: var(--color-primario);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }
        .professor-badge {
            background: linear-gradient(45deg, var(--color-primario), #0056b3);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }
        .event-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-chalkboard-teacher me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="index.php?action=eventos">
                    <i class="fas fa-calendar me-1"></i>Eventos
                </a>
                <a class="nav-link" href="index.php?action=personas">
                    <i class="fas fa-users me-1"></i>Personas
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Asignar Profesores
                </h1>
                <p class="text-muted mb-0">Gestiona la asignación de profesores a eventos</p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>

        <div class="row">
            <!-- Formulario de Asignación -->
            <div class="col-md-4">
                <div class="assignment-card">
                    <h4 class="section-title">
                        <i class="fas fa-plus me-2"></i>Nueva Asignación
                    </h4>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Seleccionar Evento -->
                        <div class="mb-3">
                            <label for="cod_evento" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Evento *
                            </label>
                            <select class="form-select" id="cod_evento" name="cod_evento" required>
                                <option value="">Selecciona un evento</option>
                                <?php foreach ($eventos as $evento): ?>
                                <option value="<?php echo $evento['cod_evento']; ?>">
                                    <?php echo htmlspecialchars($evento['nom_evento']); ?> 
                                    (<?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona un evento.
                            </div>
                        </div>

                        <!-- Seleccionar Profesor -->
                        <div class="mb-3">
                            <label for="id_profesor" class="form-label">
                                <i class="fas fa-user-tie me-1"></i>Profesor *
                            </label>
                            <select class="form-select" id="id_profesor" name="id_profesor" required>
                                <option value="">Selecciona un profesor</option>
                                <?php foreach ($profesores as $profesor): ?>
                                <option value="<?php echo $profesor['id_user']; ?>">
                                    <?php echo htmlspecialchars($profesor['nom_user']); ?>
                                    (<?php echo htmlspecialchars($profesor['correo_user']); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona un profesor.
                            </div>
                        </div>

                        <!-- Botón de Asignar -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>Asignar Profesor
                        </button>
                    </form>

                    <!-- Información de Ayuda -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-info-circle me-1"></i>Información
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Solo se muestran profesores (tipo DOC)</li>
                            <li>• Un profesor puede estar en varios eventos</li>
                            <li>• Un evento puede tener varios profesores</li>
                            <li>• No se puede asignar el mismo profesor dos veces</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Lista de Asignaciones -->
            <div class="col-md-8">
                <div class="assignment-card">
                    <h4 class="section-title">
                        <i class="fas fa-list me-2"></i>Asignaciones Actuales
                    </h4>
                    
                    <?php if (empty($asignaciones)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted mb-3">No hay asignaciones</h5>
                        <p class="text-muted">Aún no se han asignado profesores a eventos.</p>
                    </div>
                    <?php else: ?>
                    
                    <!-- Agrupar por Evento -->
                    <?php 
                    $eventosAgrupados = [];
                    foreach ($asignaciones as $asignacion) {
                        $cod_evento = $asignacion['cod_evento'];
                        if (!isset($eventosAgrupados[$cod_evento])) {
                            $eventosAgrupados[$cod_evento] = [
                                'evento' => $asignacion['nom_evento'],
                                'fecha' => $asignacion['fecha_inicio'],
                                'profesores' => []
                            ];
                        }
                        $eventosAgrupados[$cod_evento]['profesores'][] = $asignacion;
                    }
                    ?>

                    <?php foreach ($eventosAgrupados as $cod_evento => $grupo): ?>
                    <div class="event-info mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <?php echo htmlspecialchars($grupo['evento']); ?>
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($grupo['fecha'])); ?>
                                </small>
                            </div>
                            <span class="badge bg-primary">
                                <?php echo count($grupo['profesores']); ?> profesor(es)
                            </span>
                        </div>
                        
                        <div class="profesores-asignados">
                            <?php foreach ($grupo['profesores'] as $profesor): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded">
                                <div>
                                    <span class="professor-badge">
                                        <i class="fas fa-user-tie me-1"></i>
                                        <?php echo htmlspecialchars($profesor['nom_user']); ?>
                                    </span>
                                    <small class="text-muted d-block">
                                        <?php echo htmlspecialchars($profesor['correo_user']); ?>
                                    </small>
                                </div>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('¿Estás seguro de desasignar este profesor?')">
                                    <input type="hidden" name="action" value="desasignar">
                                    <input type="hidden" name="cod_evento" value="<?php echo $cod_evento; ?>">
                                    <input type="hidden" name="id_profesor" value="<?php echo $profesor['id_user']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desasignar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html> 