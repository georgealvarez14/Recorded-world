<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Disponibles - Estudiante</title>
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
                <a class="nav-link" href="index.php?accion=dashboard_estudiante">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-plus-circle"></i> Eventos Disponibles</h2>
                        <p class="mb-0">Inscríbete en nuevos eventos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtrar Eventos</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="eventos_disponibles">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="buscar" class="form-label">Buscar:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Nombre del evento...">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_desde" class="form-label">Fecha Desde:</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                                           value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_hasta" class="form-label">Fecha Hasta:</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                                           value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de eventos disponibles -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Eventos Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Evento</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Ubicación</th>
                                        <th>Capacidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Construir consulta con filtros
                                    $where_conditions = ["e.fecha_inicio >= CURDATE()"];
                                    $params = [];
                                    
                                    if (!empty($_GET['buscar'])) {
                                        $buscar = $_GET['buscar'];
                                        $where_conditions[] = "e.nom_evento LIKE ?";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    if (!empty($_GET['fecha_desde'])) {
                                        $where_conditions[] = "e.fecha_inicio >= ?";
                                        $params[] = $_GET['fecha_desde'];
                                    }
                                    
                                    if (!empty($_GET['fecha_hasta'])) {
                                        $where_conditions[] = "e.fecha_inicio <= ?";
                                        $params[] = $_GET['fecha_hasta'];
                                    }
                                    
                                    $sql = "
                                        SELECT e.*, 
                                               COUNT(p.id_user) as inscritos,
                                               CASE WHEN mi_inscripcion.id_user IS NOT NULL THEN 1 ELSE 0 END as ya_inscrito
                                        FROM evento e 
                                        LEFT JOIN participante p ON e.cod_evento = p.cod_evento
                                        LEFT JOIN participante mi_inscripcion ON e.cod_evento = mi_inscripcion.cod_evento AND mi_inscripcion.id_user = ?
                                        WHERE " . implode(" AND ", $where_conditions) . "
                                        GROUP BY e.cod_evento
                                        ORDER BY e.fecha_inicio ASC
                                    ";
                                    
                                    $params = array_merge([$_SESSION['usuario_id']], $params);
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $eventos = $stmt->fetchAll();
                                    
                                    if (empty($eventos)) {
                                        echo "<tr><td colspan='8' class='text-center text-muted'>No hay eventos disponibles</td></tr>";
                                    } else {
                                        foreach ($eventos as $evento) {
                                            $fecha = new DateTime($evento['fecha_inicio']);
                                            $hoy = new DateTime();
                                            
                                            // Determinar estado
                                            if ($evento['ya_inscrito']) {
                                                $estado = '<span class="badge bg-success">Ya Inscrito</span>';
                                            } elseif ($evento['aforo_max'] && $evento['inscritos'] >= $evento['aforo_max']) {
                                                $estado = '<span class="badge bg-danger">Completo</span>';
                                            } elseif ($fecha->format('Y-m-d') === $hoy->format('Y-m-d')) {
                                                $estado = '<span class="badge bg-warning">Hoy</span>';
                                            } else {
                                                $diferencia = $hoy->diff($fecha);
                                                $estado = '<span class="badge bg-info">En ' . $diferencia->days . ' días</span>';
                                            }
                                            
                                            // Capacidad
                                            if ($evento['aforo_max']) {
                                                $capacidad = $evento['inscritos'] . '/' . $evento['aforo_max'];
                                            } else {
                                                $capacidad = $evento['inscritos'] . '/∞';
                                            }
                                            
                                            ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($evento['nom_evento']); ?></strong>
                                                    <br><small class="text-muted"><?php echo $evento['cod_evento']; ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars(substr($evento['descripcion'], 0, 50)) . (strlen($evento['descripcion']) > 50 ? '...' : ''); ?></td>
                                                <td><?php echo $fecha->format('d/m/Y'); ?></td>
                                                <td><?php echo $evento['hora']; ?></td>
                                                <td><?php echo $evento['ubicacion'] ?? 'Por definir'; ?></td>
                                                <td><?php echo $capacidad; ?></td>
                                                <td><?php echo $estado; ?></td>
                                                <td>
                                                    <a href="index.php?accion=ver_evento_estudiante&id=<?php echo $evento['cod_evento']; ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                    
                                                    <?php if (!$evento['ya_inscrito'] && (!$evento['aforo_max'] || $evento['inscritos'] < $evento['aforo_max'])): ?>
                                                    <button class="btn btn-sm btn-success" 
                                                            onclick="inscribirseEvento('<?php echo $evento['cod_evento']; ?>', '<?php echo htmlspecialchars($evento['nom_evento']); ?>')">
                                                        <i class="fas fa-plus"></i> Inscribirse
                                                    </button>
                                                    <?php elseif ($evento['ya_inscrito']): ?>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="cancelarInscripcion('<?php echo $evento['cod_evento']; ?>', '<?php echo htmlspecialchars($evento['nom_evento']); ?>')">
                                                        <i class="fas fa-minus"></i> Cancelar
                                                    </button>
                                                    <?php else: ?>
                                                    <span class="btn btn-sm btn-secondary disabled">
                                                        <i class="fas fa-ban"></i> Completo
                                                    </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3><?php echo count($eventos); ?></h3>
                        <p class="mb-0">Eventos Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <?php
                        $inscritos = array_filter($eventos, function($e) { return $e['ya_inscrito']; });
                        ?>
                        <h3><?php echo count($inscritos); ?></h3>
                        <p class="mb-0">Ya Inscrito</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <?php
                        $hoy = array_filter($eventos, function($e) { 
                            return (new DateTime($e['fecha_inicio']))->format('Y-m-d') === (new DateTime())->format('Y-m-d'); 
                        });
                        ?>
                        <h3><?php echo count($hoy); ?></h3>
                        <p class="mb-0">Eventos Hoy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <?php
                        $disponibles = array_filter($eventos, function($e) { 
                            return !$e['ya_inscrito'] && (!$e['aforo_max'] || $e['inscritos'] < $e['aforo_max']); 
                        });
                        ?>
                        <h3><?php echo count($disponibles); ?></h3>
                        <p class="mb-0">Puedo Inscribirme</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=dashboard_estudiante" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        function inscribirseEvento(codigo, nombre) {
            if (confirm(`¿Estás seguro de inscribirte en "${nombre}"?`)) {
                window.location.href = `index.php?accion=inscribirse_evento&id=${codigo}`;
            }
        }
        
        function cancelarInscripcion(codigo, nombre) {
            if (confirm(`¿Estás seguro de cancelar tu inscripción en "${nombre}"?`)) {
                window.location.href = `index.php?accion=cancelar_inscripcion&id=${codigo}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 