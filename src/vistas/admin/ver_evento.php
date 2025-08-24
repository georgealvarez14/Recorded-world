<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Evento - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos - ADMIN</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Dashboard</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h2>👁️ Ver Evento</h2>
                        <p class="mb-0">Detalles completos del evento</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del evento -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Información del Evento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Código:</strong> <code><?php echo $evento['cod_evento']; ?></code></p>
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($evento['nom_evento']); ?></p>
                                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($evento['descripcion']); ?></p>
                                <p><strong>Duración:</strong> <?php echo $evento['duracion'] ?? 'No especificada'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?></p>
                                <p><strong>Hora:</strong> <?php echo $evento['hora']; ?></p>
                                <p><strong>Ubicación:</strong> <?php echo $evento['ubicacion'] ?? 'No especificada'; ?></p>
                                <p><strong>Aforo Máximo:</strong> <?php echo $evento['aforo_max'] ?? 'Sin límite'; ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Estado:</strong> 
                                    <?php 
                                    $fecha_evento = new DateTime($evento['fecha_inicio']);
                                    $hoy = new DateTime();
                                    if ($fecha_evento < $hoy) {
                                        echo '<span class="badge bg-secondary">Evento Pasado</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Evento Próximo</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Días restantes:</strong> 
                                    <?php 
                                    $diferencia = $hoy->diff($fecha_evento);
                                    if ($fecha_evento < $hoy) {
                                        echo 'Evento ya pasó';
                                    } else {
                                        echo $diferencia->days . ' días';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participantes del evento -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">👥 Participantes del Evento</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT p.*, rp.fecha_llegada, rp.hora_salida 
                            FROM persona p 
                            INNER JOIN participante part ON p.id_user = part.id_user 
                            LEFT JOIN registro_participante rp ON p.id_user = rp.id_user AND part.cod_evento = rp.cod_evento
                            WHERE part.cod_evento = ?
                            ORDER BY p.nom_user
                        ");
                        $stmt->execute([$evento['cod_evento']]);
                        $participantes = $stmt->fetchAll();
                        
                        if (empty($participantes)) {
                            echo '<p class="text-muted">No hay participantes registrados para este evento.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead><tr><th>Nombre</th><th>Tipo</th><th>Grado</th><th>Asistencia</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($participantes as $participante) {
                                $tipo_badge = [
                                    'ADM' => '<span class="badge bg-danger">Admin</span>',
                                    'DOC' => '<span class="badge bg-primary">Docente</span>',
                                    'EST' => '<span class="badge bg-success">Estudiante</span>',
                                    'ACU' => '<span class="badge bg-warning">Acudiente</span>'
                                ];
                                $asistencia = $participante['fecha_llegada'] ? '✅ Asistió' : '❌ No asistió';
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($participante['nom_user']) . '</td>';
                                echo '<td>' . ($tipo_badge[$participante['tipo_persona']] ?? $participante['tipo_persona']) . '</td>';
                                echo '<td>' . ($participante['cod_grado'] ?? '-') . '</td>';
                                echo '<td>' . $asistencia . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal del evento -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">👨‍🏫 Personal del Evento</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT p.* 
                            FROM persona p 
                            INNER JOIN personal_evento pe ON p.id_user = pe.id_user 
                            WHERE pe.cod_evento = ?
                            ORDER BY p.nom_user
                        ");
                        $stmt->execute([$evento['cod_evento']]);
                        $personal = $stmt->fetchAll();
                        
                        if (empty($personal)) {
                            echo '<p class="text-muted">No hay personal asignado para este evento.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead><tr><th>Nombre</th><th>Tipo</th><th>Email</th><th>Teléfono</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($personal as $persona) {
                                $tipo_badge = [
                                    'ADM' => '<span class="badge bg-danger">Admin</span>',
                                    'DOC' => '<span class="badge bg-primary">Docente</span>',
                                    'EST' => '<span class="badge bg-success">Estudiante</span>',
                                    'ACU' => '<span class="badge bg-warning">Acudiente</span>'
                                ];
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($persona['nom_user']) . '</td>';
                                echo '<td>' . ($tipo_badge[$persona['tipo_persona']] ?? $persona['tipo_persona']) . '</td>';
                                echo '<td>' . htmlspecialchars($persona['correo_user']) . '</td>';
                                echo '<td>' . ($persona['telef_user'] ?? '-') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="index.php?accion=editar_evento_admin&id=<?php echo $evento['cod_evento']; ?>" class="btn btn-primary">
                        ✏️ Editar Evento
                    </a>
                    <a href="index.php?accion=crud_eventos" class="btn btn-secondary">
                        ← Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 