<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos del Estudiante - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .event-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-3px);
        }
        .event-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .attendance-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .attendance-present {
            background: var(--color-exito);
            color: white;
        }
        .attendance-absent {
            background: var(--color-peligro);
            color: white;
        }
        .attendance-pending {
            background: var(--color-advertencia);
            color: white;
        }
        .event-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-user-friends me-2"></i>Panel Acudiente
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?action=acudiente-estudiantes">
                    <i class="fas fa-users me-1"></i>Mis Estudiantes
                </a>
                <a class="nav-link" href="index.php?action=acudiente-eventos">
                    <i class="fas fa-calendar me-1"></i>Eventos
                </a>
                <a class="nav-link" href="index.php?action=acudiente-perfil">
                    <i class="fas fa-user me-1"></i>Mi Perfil
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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>Eventos del Estudiante
                </h1>
                <p class="text-muted mb-0">
                    Seguimiento de eventos para: <strong><?php echo htmlspecialchars($estudiante['nom_user']); ?></strong>
                </p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>

        <!-- Student Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($estudiante['nom_user']); ?></h4>
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    <?php echo htmlspecialchars($estudiante['grado_nombre'] ?? 'Sin grado'); ?>
                                    <?php if ($estudiante['grupo']): ?>
                                        - Grupo <?php echo $estudiante['grupo']; ?>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($estudiante['ciudad_nombre'] ?? 'Sin ciudad'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="text-primary mb-0"><?php echo $estadisticas['total_eventos']; ?></h5>
                                <small class="text-muted">Total</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-success mb-0"><?php echo $estadisticas['eventos_asistidos']; ?></h5>
                                <small class="text-muted">Asistidos</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-warning mb-0"><?php echo $estadisticas['proximos_eventos']; ?></h5>
                                <small class="text-muted">Próximos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events List -->
        <div class="row">
            <div class="col-12">
                <?php if (empty($eventos)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted mb-3">No hay eventos registrados</h5>
                    <p class="text-muted">Este estudiante no está inscrito en ningún evento actualmente.</p>
                    <a href="index.php?action=eventos" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Buscar Eventos Disponibles
                    </a>
                </div>
                <?php else: ?>
                
                <?php foreach ($eventos as $evento): ?>
                <div class="event-card">
                    <div class="event-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                                    <?php echo htmlspecialchars($evento['nom_evento']); ?>
                                </h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                            </div>
                            <div class="text-end">
                                <?php
                                $fecha_evento = new DateTime($evento['fecha_inicio']);
                                $hoy = new DateTime();
                                $asistencia = '';
                                
                                if ($evento['fecha_llegada']) {
                                    $asistencia = '<span class="attendance-badge attendance-present">Asistió</span>';
                                } elseif ($fecha_evento < $hoy) {
                                    $asistencia = '<span class="attendance-badge attendance-absent">No Asistió</span>';
                                } else {
                                    $asistencia = '<span class="attendance-badge attendance-pending">Pendiente</span>';
                                }
                                echo $asistencia;
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="event-details">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Información del Evento
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Fecha:</small><br>
                                        <strong><?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?></strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Hora:</small><br>
                                        <strong><?php echo date('H:i', strtotime($evento['hora'])); ?></strong>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted">Duración:</small><br>
                                        <strong><?php echo htmlspecialchars($evento['duracion']); ?></strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Materia:</small><br>
                                        <strong><?php echo htmlspecialchars($evento['materia_nombre'] ?? 'N/A'); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="event-details">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ubicación y Asistencia
                                </h6>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">Ubicación:</small><br>
                                        <strong><?php echo htmlspecialchars($evento['ubicacion']); ?></strong>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">Jornada:</small><br>
                                        <strong><?php echo htmlspecialchars($evento['jornada_nombre'] ?? 'N/A'); ?></strong>
                                    </div>
                                    <?php if ($evento['fecha_llegada']): ?>
                                    <div class="col-12">
                                        <small class="text-muted">Hora de llegada:</small><br>
                                        <strong class="text-success">
                                            <?php echo date('d/m/Y H:i', strtotime($evento['fecha_llegada'])); ?>
                                        </strong>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    Aforo: <?php echo $evento['aforo_max']; ?> personas
                                </small>
                            </div>
                            <a href="index.php?action=eventos&controller=view&id=<?php echo $evento['cod_evento']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Ver Detalles del Evento
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 