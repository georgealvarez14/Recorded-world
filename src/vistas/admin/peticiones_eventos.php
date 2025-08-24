<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peticiones de Eventos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-crown me-2"></i>Sistema de Eventos - ADMIN
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="section-title">
            <h2><i class="fas fa-calendar-plus me-3"></i>Peticiones de Eventos</h2>
            <p>Gestiona las solicitudes de eventos enviadas por los docentes</p>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="index.php">
                    <input type="hidden" name="accion" value="peticiones_eventos_admin">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-select" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="PENDIENTE" <?php echo ($_GET['estado'] ?? '') === 'PENDIENTE' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="APROBADA" <?php echo ($_GET['estado'] ?? '') === 'APROBADA' ? 'selected' : ''; ?>>Aprobada</option>
                                <option value="RECHAZADA" <?php echo ($_GET['estado'] ?? '') === 'RECHAZADA' ? 'selected' : ''; ?>>Rechazada</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="buscar" 
                                   value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                   placeholder="Buscar por nombre de evento...">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de peticiones -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Peticiones</h5>
            </div>
            <div class="card-body">
                <?php
                // Construir consulta con filtros
                $where = "WHERE 1=1";
                $params = [];
                
                if (!empty($_GET['estado'])) {
                    $where .= " AND pe.estado = ?";
                    $params[] = $_GET['estado'];
                }
                
                if (!empty($_GET['buscar'])) {
                    $where .= " AND pe.nombre_evento LIKE ?";
                    $params[] = '%' . $_GET['buscar'] . '%';
                }
                
                $sql = "SELECT pe.*, p.nom_user as docente_nombre 
                        FROM peticiones_evento pe 
                        JOIN persona p ON pe.docente_id = p.id_user 
                        $where 
                        ORDER BY pe.fecha_peticion DESC";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $peticiones = $stmt->fetchAll();
                ?>
                
                <?php if (empty($peticiones)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay peticiones de eventos</h5>
                        <p class="text-muted">Los docentes pueden enviar solicitudes de eventos desde su panel</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Docente</th>
                                    <th>Fecha Propuesta</th>
                                    <th>Materia</th>
                                    <th>Estado</th>
                                    <th>Fecha Petición</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peticiones as $peticion): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($peticion['nombre_evento']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($peticion['descripcion']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($peticion['docente_nombre']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($peticion['fecha_propuesta'])); ?>
                                        <br>
                                        <small class="text-muted"><?php echo $peticion['hora_propuesta']; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($peticion['materia']); ?></td>
                                    <td>
                                        <?php
                                        $estadoClass = '';
                                        $estadoIcon = '';
                                        switch ($peticion['estado']) {
                                            case 'PENDIENTE':
                                                $estadoClass = 'warning';
                                                $estadoIcon = 'clock';
                                                break;
                                            case 'APROBADA':
                                                $estadoClass = 'success';
                                                $estadoIcon = 'check';
                                                break;
                                            case 'RECHAZADA':
                                                $estadoClass = 'danger';
                                                $estadoIcon = 'times';
                                                break;
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $estadoClass; ?>">
                                            <i class="fas fa-<?php echo $estadoIcon; ?> me-1"></i>
                                            <?php echo $peticion['estado']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($peticion['fecha_peticion'])); ?></td>
                                    <td>
                                        <?php if ($peticion['estado'] === 'PENDIENTE'): ?>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?accion=aprobar_peticion_admin&id=<?php echo $peticion['id']; ?>" 
                                                   class="btn btn-success btn-sm" 
                                                   onclick="return confirm('¿Aprobar esta petición de evento?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="index.php?accion=rechazar_peticion_admin&id=<?php echo $peticion['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('¿Rechazar esta petición de evento?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Procesada</span>
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

        <!-- Botón volver -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
