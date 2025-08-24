<?php

$cod_evento = $_GET['id'] ?? '';
if (empty($cod_evento)) {
    header('Location: index.php?action=eventos');
    exit;
}

// Obtener detalles del evento
$sql = "SELECT e.*, m.descripcion as materia_nombre, tj.descripcion as jornada_nombre 
        FROM evento e 
        LEFT JOIN materias m ON e.materia = m.cod_categoria 
        LEFT JOIN tipo_jornada tj ON e.tipo_jornada = tj.cod_jornada 
        WHERE e.cod_evento = ?";
$evento = $db->fetch($sql, [$cod_evento]);

if (!$evento) {
    header('Location: index.php?action=eventos');
    exit;
}

// Obtener participantes del evento
$sql_participantes = "SELECT p.*, pe.fecha_llegada, pe.hora_salida 
                      FROM participante par 
                      JOIN persona p ON par.id_user = p.id_user 
                      LEFT JOIN registro_participante pe ON par.cod_evento = pe.cod_evento AND par.id_user = pe.id_user
                      WHERE par.cod_evento = ? 
                      ORDER BY p.nom_user";
$participantes = $db->fetchAll($sql_participantes, [$cod_evento]);

// Obtener personal asignado al evento
$sql_personal = "SELECT p.* 
                 FROM personal_evento pe 
                 JOIN persona p ON pe.id_user = p.id_user 
                 WHERE pe.cod_evento = ? 
                 ORDER BY p.nom_user";
$personal = $db->fetchAll($sql_personal, [$cod_evento]);

// Contar participantes
$total_participantes = count($participantes);
$participantes_registrados = count(array_filter($participantes, function($p) {
    return !empty($p['fecha_llegada']);
}));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($evento['nom_evento']); ?> - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema de Gestión</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Inicio</a>
                <a class="nav-link" href="index.php?action=eventos">Eventos</a>
                <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a class="nav-link" href="index.php?action=logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Información del Evento -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($evento['nom_evento']); ?>
                        </h3>
                        <?php if ($_SESSION['user_type'] === 'ADM'): ?>
                        <div>
                            <a href="index.php?action=eventos&controller=edit&id=<?php echo $evento['cod_evento']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($evento['foto_evento'])): ?>
                        <div class="text-center mb-4">
                            <img src="<?php echo htmlspecialchars($evento['foto_evento']); ?>" class="img-fluid rounded" alt="Foto del evento" style="max-height: 300px;">
                        </div>
                        <?php endif; ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5><i class="fas fa-info-circle text-primary"></i> Descripción</h5>
                                <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-book text-success"></i> Materia</h5>
                                <p><?php echo htmlspecialchars($evento['materia_nombre'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h5><i class="fas fa-calendar text-info"></i> Fecha</h5>
                                <p>
                                    <strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?><br>
                                    <strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($evento['fecha_final'])); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fas fa-clock text-warning"></i> Horario</h5>
                                <p>
                                    <strong>Hora:</strong> <?php echo date('H:i', strtotime($evento['hora'])); ?><br>
                                    <strong>Duración:</strong> <?php echo htmlspecialchars($evento['duracion']); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fas fa-sun text-warning"></i> Jornada</h5>
                                <p><?php echo htmlspecialchars($evento['jornada_nombre'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5><i class="fas fa-map-marker-alt text-danger"></i> Ubicación</h5>
                                <p><?php echo htmlspecialchars($evento['ubicacion']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-users text-success"></i> Aforo</h5>
                                <p>
                                    <strong>Máximo:</strong> <?php echo $evento['aforo_max']; ?> personas<br>
                                    <strong>Inscritos:</strong> <?php echo $total_participantes; ?> personas
                                </p>
                            </div>
                        </div>
                        
                        <!-- Barra de progreso del aforo -->
                        <div class="mb-3">
                            <label class="form-label">Ocupación del evento</label>
                            <div class="progress">
                                <?php 
                                $porcentaje = ($total_participantes / $evento['aforo_max']) * 100;
                                $color = $porcentaje > 90 ? 'danger' : ($porcentaje > 70 ? 'warning' : 'success');
                                ?>
                                <div class="progress-bar bg-<?php echo $color; ?>" role="progressbar" 
                                     style="width: <?php echo $porcentaje; ?>%" 
                                     aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo round($porcentaje, 1); ?>%
                                </div>
                            </div>
                            <small class="text-muted"><?php echo $total_participantes; ?> de <?php echo $evento['aforo_max']; ?> cupos ocupados</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel lateral -->
            <div class="col-lg-4">
                <!-- Estadísticas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary"><?php echo $total_participantes; ?></h4>
                                    <small class="text-muted">Inscritos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?php echo $participantes_registrados; ?></h4>
                                <small class="text-muted">Asistieron</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal del Evento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-user-tie"></i> Personal Asignado</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($personal)): ?>
                        <p class="text-muted">No hay personal asignado</p>
                        <?php else: ?>
                        <ul class="list-unstyled">
                            <?php foreach ($personal as $persona): ?>
                            <li class="mb-2">
                                <i class="fas fa-user text-primary"></i>
                                <?php echo htmlspecialchars($persona['nom_user']); ?>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($persona['tipo_persona']); ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Acciones -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-cogs"></i> Acciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if ($_SESSION['user_type'] === 'EST'): ?>
                            <button class="btn btn-primary" onclick="inscribirseEvento('<?php echo $evento['cod_evento']; ?>')">
                                <i class="fas fa-plus"></i> Inscribirse
                            </button>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['user_type'] === 'DOC' || $_SESSION['user_type'] === 'ADM'): ?>
                                            <a href="index.php?action=participantes&controller=registrar&evento=<?php echo $evento['cod_evento']; ?>" 
                   class="btn btn-success btn-lg" 
                   style="font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
                    <i class="fas fa-clipboard-check me-2"></i> REGISTRAR ASISTENCIA
                </a>
                
                <?php if ($_SESSION['user_type'] === 'ADM'): ?>
                <form method="POST" action="index.php?action=generar-qr-evento" class="d-inline">
                    <input type="hidden" name="cod_evento" value="<?php echo $evento['cod_evento']; ?>">
                    <button type="submit" class="btn btn-info btn-lg ms-2" 
                            style="font-weight: bold; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);">
                        <i class="fas fa-qrcode me-2"></i> GENERAR QR
                    </button>
                </form>
                <?php endif; ?>
                            <?php endif; ?>
                            
                            <a href="index.php?action=eventos" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Eventos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de Participantes -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-users"></i> Participantes (<?php echo $total_participantes; ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($participantes)): ?>
                <p class="text-muted">No hay participantes inscritos en este evento.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Grado</th>
                                <th>Fecha de Llegada</th>
                                <th>Hora de Salida</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participantes as $participante): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($participante['foto_persona'])): ?>
                                    <img src="<?php echo htmlspecialchars($participante['foto_persona']); ?>" 
                                         class="rounded-circle me-2" width="30" height="30" alt="Foto">
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($participante['nom_user']); ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $participante['tipo_persona'] === 'EST' ? 'primary' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($participante['tipo_persona']); ?>
                                    </span>
                                </td>
                                <td><?php echo $participante['cod_grado'] ?? 'N/A'; ?></td>
                                <td>
                                    <?php if (!empty($participante['fecha_llegada'])): ?>
                                    <span class="text-success">
                                        <i class="fas fa-check"></i> 
                                        <?php echo date('d/m/Y H:i', strtotime($participante['fecha_llegada'])); ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-muted">No registrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($participante['hora_salida'])): ?>
                                    <span class="text-info">
                                        <?php echo date('d/m/Y H:i', strtotime($participante['hora_salida'])); ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($participante['fecha_llegada'])): ?>
                                    <span class="badge bg-success">Asistió</span>
                                    <?php else: ?>
                                    <span class="badge bg-warning">Inscrito</span>
                                    <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function inscribirseEvento(codEvento) {
        if (confirm('¿Deseas inscribirte en este evento?')) {
            // Aquí iría la lógica para inscribirse
            alert('Función de inscripción en desarrollo');
        }
    }
    </script>
</body>
</html> 