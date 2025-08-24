<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Eventos - Estudiante</title>
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
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-calendar-alt"></i> Mis Eventos</h2>
                        <p class="mb-0">Eventos en los que estoy inscrito</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Filtrar Eventos</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="mis_eventos">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado:</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="">Todos</option>
                                        <option value="proximos" <?php echo ($_GET['estado'] ?? '') === 'proximos' ? 'selected' : ''; ?>>Próximos</option>
                                        <option value="pasados" <?php echo ($_GET['estado'] ?? '') === 'pasados' ? 'selected' : ''; ?>>Pasados</option>
                                        <option value="hoy" <?php echo ($_GET['estado'] ?? '') === 'hoy' ? 'selected' : ''; ?>>Hoy</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="buscar" class="form-label">Buscar:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Nombre del evento...">
                                </div>
                                <div class="col-md-4">
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

        <!-- Tabla de eventos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Mis Eventos</h5>
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
                                        <th>Asistencia</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Construir consulta con filtros
                                    $where_conditions = ["p.id_user = ?"];
                                    $params = [$_SESSION['usuario_id']];
                                    
                                    if (!empty($_GET['buscar'])) {
                                        $buscar = $_GET['buscar'];
                                        $where_conditions[] = "e.nom_evento LIKE ?";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    if (!empty($_GET['estado'])) {
                                        switch ($_GET['estado']) {
                                            case 'proximos':
                                                $where_conditions[] = "e.fecha_inicio > CURDATE()";
                                                break;
                                            case 'pasados':
                                                $where_conditions[] = "e.fecha_inicio < CURDATE()";
                                                break;
                                            case 'hoy':
                                                $where_conditions[] = "e.fecha_inicio = CURDATE()";
                                                break;
                                        }
                                    }
                                    
                                    $sql = "
                                        SELECT e.*, p.id_user, rp.fecha_llegada, rp.hora_salida 
                                        FROM evento e 
                                        INNER JOIN participante p ON e.cod_evento = p.cod_evento 
                                        LEFT JOIN registro_participante rp ON e.cod_evento = rp.cod_evento AND p.id_user = rp.id_user
                                        WHERE " . implode(" AND ", $where_conditions) . "
                                        ORDER BY e.fecha_inicio DESC
                                    ";
                                    
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $eventos = $stmt->fetchAll();
                                    
                                    if (empty($eventos)) {
                                        echo "<tr><td colspan='8' class='text-center text-muted'>No tienes eventos inscritos</td></tr>";
                                    } else {
                                        foreach ($eventos as $evento) {
                                            $fecha = new DateTime($evento['fecha_inicio']);
                                            $hoy = new DateTime();
                                            
                                            // Determinar estado
                                            if ($fecha < $hoy) {
                                                $estado = '<span class="badge bg-secondary">Pasado</span>';
                                            } elseif ($fecha->format('Y-m-d') === $hoy->format('Y-m-d')) {
                                                $estado = '<span class="badge bg-danger">Hoy</span>';
                                            } else {
                                                $diferencia = $hoy->diff($fecha);
                                                $estado = '<span class="badge bg-success">En ' . $diferencia->days . ' días</span>';
                                            }
                                            
                                            // Determinar asistencia
                                            if ($evento['fecha_llegada']) {
                                                $asistencia = '<span class="badge bg-success"><i class="fas fa-check"></i> Asistió</span>';
                                            } else {
                                                if ($fecha < $hoy) {
                                                    $asistencia = '<span class="badge bg-danger"><i class="fas fa-times"></i> No asistió</span>';
                                                } else {
                                                    $asistencia = '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pendiente</span>';
                                                }
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
                                                <td><?php echo $asistencia; ?></td>
                                                <td><?php echo $estado; ?></td>
                                                <td>
                                                    <a href="index.php?accion=ver_evento_estudiante&id=<?php echo $evento['cod_evento']; ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                    <?php if ($fecha->format('Y-m-d') === $hoy->format('Y-m-d') && !$evento['fecha_llegada']): ?>
                                                    <a href="index.php?accion=registrar_asistencia" 
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-qrcode"></i> Asistir
                                                    </a>
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
                        <p class="mb-0">Total Eventos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <?php
                        $asistencias = array_filter($eventos, function($e) { return $e['fecha_llegada']; });
                        ?>
                        <h3><?php echo count($asistencias); ?></h3>
                        <p class="mb-0">Asistencias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <?php
                        $proximos = array_filter($eventos, function($e) { 
                            return new DateTime($e['fecha_inicio']) > new DateTime(); 
                        });
                        ?>
                        <h3><?php echo count($proximos); ?></h3>
                        <p class="mb-0">Próximos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <?php
                        $porcentaje = count($eventos) > 0 ? round((count($asistencias) / count($eventos)) * 100) : 0;
                        ?>
                        <h3><?php echo $porcentaje; ?>%</h3>
                        <p class="mb-0">Asistencia</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 