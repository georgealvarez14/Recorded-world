<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Eventos - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt me-2"></i>Sistema de Eventos - ADMIN
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-danger text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-calendar-plus me-3"></i>Gestión de Eventos
                    </h1>
                    <p class="lead mb-4">
                        Administra y controla todos los eventos escolares del sistema de manera eficiente y profesional
                    </p>
                    <div class="d-flex gap-3">
                        <a href="index.php?accion=crear_evento_admin" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Crear Nuevo Evento
                        </a>
                        <a href="#estadisticas" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-chart-bar me-2"></i>Ver Estadísticas
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="hero-image">
                        <i class="fas fa-calendar-check" style="font-size: 6rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
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

        <!-- Estadísticas Rápidas -->
        <section id="estadisticas" class="py-4 bg-light rounded">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-calendar-alt text-primary" style="font-size: 2.5rem;"></i>
                            <h4 class="mt-2"><?php 
                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento");
                                echo $stmt->fetch()['total'];
                            ?></h4>
                            <p class="text-muted">Total Eventos</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-calendar-day text-success" style="font-size: 2.5rem;"></i>
                            <h4 class="mt-2"><?php 
                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento WHERE fecha_inicio >= CURDATE()");
                                echo $stmt->fetch()['total'];
                            ?></h4>
                            <p class="text-muted">Próximos Eventos</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-calendar-check text-warning" style="font-size: 2.5rem;"></i>
                            <h4 class="mt-2"><?php 
                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento WHERE fecha_inicio = CURDATE()");
                                echo $stmt->fetch()['total'];
                            ?></h4>
                            <p class="text-muted">Eventos Hoy</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-history text-info" style="font-size: 2.5rem;"></i>
                            <h4 class="mt-2"><?php 
                                $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento WHERE fecha_inicio < CURDATE()");
                                echo $stmt->fetch()['total'];
                            ?></h4>
                            <p class="text-muted">Eventos Pasados</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Buscador y filtros mejorado -->
        <section class="py-4">
            <div class="container">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>Buscar y Filtrar Eventos
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="accion" value="crud_eventos">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="buscar" class="form-label">
                                        <i class="fas fa-search me-1"></i>Buscar:
                                    </label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" 
                                           value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                           placeholder="Nombre, descripción, código...">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_desde" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Fecha Desde:
                                    </label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                                           value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_hasta" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Fecha Hasta:
                                    </label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                                           value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tabla de eventos mejorada -->
        <section class="py-4">
            <div class="container">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Eventos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-hashtag me-1"></i>Código</th>
                                        <th><i class="fas fa-calendar me-1"></i>Nombre</th>
                                        <th><i class="fas fa-info-circle me-1"></i>Descripción</th>
                                        <th><i class="fas fa-calendar-day me-1"></i>Fecha</th>
                                        <th><i class="fas fa-clock me-1"></i>Hora</th>
                                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Construir consulta con filtros
                                    $where_conditions = [];
                                    $params = [];
                                    
                                    if (!empty($_GET['buscar'])) {
                                        $buscar = $_GET['buscar'];
                                        $where_conditions[] = "(nom_evento LIKE ? OR descripcion LIKE ? OR cod_evento LIKE ?)";
                                        $params[] = "%$buscar%";
                                        $params[] = "%$buscar%";
                                        $params[] = "%$buscar%";
                                    }
                                    
                                    if (!empty($_GET['fecha_desde'])) {
                                        $where_conditions[] = "fecha_inicio >= ?";
                                        $params[] = $_GET['fecha_desde'];
                                    }
                                    
                                    if (!empty($_GET['fecha_hasta'])) {
                                        $where_conditions[] = "fecha_inicio <= ?";
                                        $params[] = $_GET['fecha_hasta'];
                                    }
                                    
                                    $sql = "SELECT * FROM evento";
                                    if (!empty($where_conditions)) {
                                        $sql .= " WHERE " . implode(" AND ", $where_conditions);
                                    }
                                    $sql .= " ORDER BY fecha_inicio DESC";
                                    
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $eventos = $stmt->fetchAll();
                                    
                                    if (empty($eventos)) {
                                        echo "<tr><td colspan='6' class='text-center text-muted'>No se encontraron eventos</td></tr>";
                                    } else {
                                        foreach ($eventos as $evento) {
                                            $fecha = new DateTime($evento['fecha_inicio']);
                                            $hoy = new DateTime();
                                            $estado = $fecha < $hoy ? 'Pasado' : 'Próximo';
                                            $estado_badge = $fecha < $hoy ? 'bg-secondary' : 'bg-success';
                                            ?>
                                            <tr>
                                                <td><code><?php echo $evento['cod_evento']; ?></code></td>
                                                <td><?php echo htmlspecialchars($evento['nom_evento']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($evento['descripcion'], 0, 50)) . (strlen($evento['descripcion']) > 50 ? '...' : ''); ?></td>
                                                <td><?php echo $fecha->format('d/m/Y'); ?></td>
                                                <td><?php echo $evento['hora']; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $estado_badge; ?>"><?php echo $estado; ?></span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="index.php?accion=ver_evento_admin&id=<?php echo $evento['cod_evento']; ?>" 
                                                           class="btn btn-sm btn-info" title="Ver información completa">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="index.php?accion=editar_evento_admin&id=<?php echo $evento['cod_evento']; ?>" 
                                                           class="btn btn-sm btn-primary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if (isset($evento['qr']) && $evento['qr'] && file_exists($evento['qr'])): ?>
                                                            <a href="<?php echo $evento['qr']; ?>" 
                                                               class="btn btn-success btn-sm" title="Ver QR" target="_blank">
                                                                <i class="fas fa-qrcode"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="index.php?accion=generar_qr_evento&id=<?php echo $evento['cod_evento']; ?>" 
                                                               class="btn btn-warning btn-sm" title="Generar QR">
                                                                <i class="fas fa-qrcode"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-danger" 
                                                                onclick="eliminarEvento('<?php echo $evento['cod_evento']; ?>', '<?php echo htmlspecialchars($evento['nom_evento']); ?>')"
                                                                title="Eliminar evento">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
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
        </section>

        <!-- Botón volver mejorado -->
        <section class="py-4">
            <div class="container text-center">
                <a href="index.php?accion=inicio" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </section>
    </div>

    <script>
        function eliminarEvento(codigo, nombre) {
            if (confirm(`¿Estás seguro de eliminar el evento "${nombre}"? Esta acción no se puede deshacer.`)) {
                window.location.href = `index.php?accion=eliminar_evento_admin&id=${codigo}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .hero-image {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .stat-item {
            transition: transform 0.3s ease;
            padding: 1rem;
            border-radius: 10px;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .btn-group .btn {
            transition: transform 0.2s ease;
        }
        
        .btn-group .btn:hover {
            transform: scale(1.1);
        }
        
        .card {
            transition: box-shadow 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</body>
</html> 