<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Sistema de Eventos - ESTUDIANTE
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="nav-link">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                </span>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título de bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-user-graduate"></i> ¡Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>!</h2>
                        <p class="mb-0">Gestiona tus eventos y actividades académicas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del estudiante -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Mi Información</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("SELECT p.*, g.descripcion as nombre_grado FROM persona p LEFT JOIN grado g ON p.cod_grado = g.cod_grado WHERE p.id_user = ?");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $estudiante = $stmt->fetch();
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-id-card"></i> ID:</strong> <?php echo $estudiante['id_user']; ?></p>
                                <p><strong><i class="fas fa-user"></i> Nombre:</strong> <?php echo htmlspecialchars($estudiante['nom_user']); ?></p>
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($estudiante['correo_user']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-graduation-cap"></i> Grado:</strong> <?php echo $estudiante['nombre_grado'] ?? 'No asignado'; ?></p>
                                <p><strong><i class="fas fa-phone"></i> Teléfono:</strong> <?php echo $estudiante['telef_user'] ?? 'No registrado'; ?></p>
                                <p><strong><i class="fas fa-map-marker-alt"></i> Ciudad:</strong> <?php echo $estudiante['ciudad'] ?? 'No registrada'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funcionalidades principales -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Mis Eventos</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?accion=mis_eventos" class="btn btn-primary">
                                <i class="fas fa-list"></i> Ver Mis Eventos
                            </a>
                            <a href="index.php?accion=eventos_disponibles" class="btn btn-success">
                                <i class="fas fa-plus"></i> Eventos Disponibles
                            </a>
                            <a href="index.php?accion=registrar_asistencia" class="btn btn-warning">
                                <i class="fas fa-qrcode"></i> Registrar Asistencia
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Mi Progreso</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?accion=mi_asistencia" class="btn btn-info">
                                <i class="fas fa-clipboard-check"></i> Mi Asistencia
                            </a>
                            <a href="index.php?accion=mi_historial" class="btn btn-secondary">
                                <i class="fas fa-history"></i> Mi Historial
                            </a>
                            <a href="index.php?accion=mi_perfil" class="btn btn-dark">
                                <i class="fas fa-user-edit"></i> Editar Mi Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM participante WHERE id_user = ?");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $total_eventos = $stmt->fetch()['total'];
                        ?>
                        <h3><i class="fas fa-calendar"></i> <?php echo $total_eventos; ?></h3>
                        <p class="mb-0">Eventos Inscritos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM registro_participante WHERE id_user = ?");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $asistencias = $stmt->fetch()['total'];
                        ?>
                        <h3><i class="fas fa-check-circle"></i> <?php echo $asistencias; ?></h3>
                        <p class="mb-0">Asistencias</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM participante p INNER JOIN evento e ON p.cod_evento = e.cod_evento WHERE p.id_user = ? AND e.fecha_inicio >= CURDATE()");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $proximos = $stmt->fetch()['total'];
                        ?>
                        <h3><i class="fas fa-clock"></i> <?php echo $proximos; ?></h3>
                        <p class="mb-0">Próximos Eventos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <?php
                        $porcentaje = $total_eventos > 0 ? round(($asistencias / $total_eventos) * 100) : 0;
                        ?>
                        <h3><i class="fas fa-percentage"></i> <?php echo $porcentaje; ?>%</h3>
                        <p class="mb-0">Asistencia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximos eventos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Mis Próximos Eventos</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT e.*, p.id_user 
                            FROM evento e 
                            INNER JOIN participante p ON e.cod_evento = p.cod_evento 
                            WHERE p.id_user = ? AND e.fecha_inicio >= CURDATE()
                            ORDER BY e.fecha_inicio ASC 
                            LIMIT 5
                        ");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $proximos_eventos = $stmt->fetchAll();
                        
                        if (empty($proximos_eventos)) {
                            echo '<p class="text-muted text-center">No tienes eventos próximos programados.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped">';
                            echo '<thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th><th>Ubicación</th><th>Estado</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($proximos_eventos as $evento) {
                                $fecha = new DateTime($evento['fecha_inicio']);
                                $hoy = new DateTime();
                                $diferencia = $hoy->diff($fecha);
                                
                                if ($diferencia->days == 0) {
                                    $estado = '<span class="badge bg-danger">Hoy</span>';
                                } elseif ($diferencia->days == 1) {
                                    $estado = '<span class="badge bg-warning">Mañana</span>';
                                } else {
                                    $estado = '<span class="badge bg-info">En ' . $diferencia->days . ' días</span>';
                                }
                                
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($evento['nom_evento']) . '</td>';
                                echo '<td>' . $fecha->format('d/m/Y') . '</td>';
                                echo '<td>' . $evento['hora'] . '</td>';
                                echo '<td>' . ($evento['ubicacion'] ?? 'Por definir') . '</td>';
                                echo '<td>' . $estado . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=inicio" class="btn btn-secondary">
                <i class="fas fa-home"></i> Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 