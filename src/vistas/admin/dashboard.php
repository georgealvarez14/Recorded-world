<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sistema de Eventos</title>
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
                <span class="navbar-text">
                    <i class="fas fa-user-shield me-2"></i>Admin: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                </span>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título principal -->
        <div class="section-title">
            <h2><i class="fas fa-crown me-3"></i>Panel de Administración</h2>
            <p>Control completo del sistema de eventos educativos</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="stats-grid">
            <div class="stat-item primary">
                <h3><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona");
                    echo $stmt->fetch()['total'];
                ?></h3>
                <p><i class="fas fa-users me-2"></i>Total Personas</p>
            </div>
            
            <div class="stat-item success">
                <h3><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento");
                    echo $stmt->fetch()['total'];
                ?></h3>
                <p><i class="fas fa-calendar-alt me-2"></i>Total Eventos</p>
            </div>
            
            <div class="stat-item info">
                <h3><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'EST'");
                    echo $stmt->fetch()['total'];
                ?></h3>
                <p><i class="fas fa-user-graduate me-2"></i>Estudiantes</p>
            </div>
            
            <div class="stat-item secondary">
                <h3><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'DOC'");
                    echo $stmt->fetch()['total'];
                ?></h3>
                <p><i class="fas fa-chalkboard-teacher me-2"></i>Docentes</p>
            </div>
            
            <div class="stat-item warning">
                <h3><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM peticiones_evento WHERE estado = 'PENDIENTE'");
                    echo $stmt->fetch()['total'];
                ?></h3>
                <p><i class="fas fa-clock me-2"></i>Peticiones Pendientes</p>
            </div>
        </div>

        <!-- Gestión Principal -->
        <div class="admin-grid">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-users" style="background: var(--admin-primary);"></i>
                    <h5>Gestión de Personas</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=crud_personas" class="btn btn-primary w-100">
                        <i class="fas fa-list me-2"></i>Ver Todas las Personas
                    </a>
                    <a href="index.php?accion=crear_persona_admin" class="btn btn-success w-100">
                        <i class="fas fa-user-plus me-2"></i>Crear Nueva Persona
                    </a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-calendar-alt" style="background: var(--admin-success);"></i>
                    <h5>Gestión de Eventos</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=crud_eventos" class="btn btn-success w-100">
                        <i class="fas fa-list me-2"></i>Ver Todos los Eventos
                    </a>
                    <a href="index.php?accion=crear_evento_admin" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Evento
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestión de Tablas Maestras -->
        <div class="admin-grid">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-book" style="background: var(--admin-warning);"></i>
                    <h5>Gestión de Materias</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=crud_materias" class="btn btn-warning w-100">
                        <i class="fas fa-list me-2"></i>Ver Todas las Materias
                    </a>
                    <a href="index.php?accion=crear_materia_admin" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i>Crear Nueva Materia
                    </a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-graduation-cap" style="background: var(--admin-info);"></i>
                    <h5>Gestión de Grados</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=crud_grados" class="btn btn-info w-100">
                        <i class="fas fa-list me-2"></i>Ver Todos los Grados
                    </a>
                    <a href="index.php?accion=crear_grado_admin" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Grado
                    </a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-city" style="background: var(--admin-secondary);"></i>
                    <h5>Gestión de Ciudades</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=crud_ciudades" class="btn btn-secondary w-100">
                        <i class="fas fa-list me-2"></i>Ver Todas las Ciudades
                    </a>
                    <a href="index.php?accion=crear_ciudad_admin" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i>Crear Nueva Ciudad
                    </a>
                </div>
            </div>
        </div>

        <!-- Funciones Especiales -->
        <div class="admin-grid">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-qrcode" style="background: var(--admin-warning);"></i>
                    <h5>Sistema de Códigos QR</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=generar_qr" class="btn btn-warning w-100">
                        <i class="fas fa-qrcode me-2"></i>Generar Códigos QR
                    </a>
                    <a href="index.php?accion=ver_qr" class="btn btn-info w-100">
                        <i class="fas fa-eye me-2"></i>Ver Códigos QR
                    </a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-door-open" style="background: var(--admin-danger);"></i>
                    <h5>Control de Entrada</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=control_entrada" class="btn btn-danger w-100">
                        <i class="fas fa-camera me-2"></i>Escáner de Entrada
                    </a>
                    <a href="index.php?accion=reportes_entrada" class="btn btn-secondary w-100">
                        <i class="fas fa-chart-bar me-2"></i>Reportes de Entrada
                    </a>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-calendar-check" style="background: var(--admin-success);"></i>
                    <h5>Peticiones de Eventos</h5>
                </div>
                <div class="admin-card-body">
                    <a href="index.php?accion=peticiones_eventos_admin" class="btn btn-success w-100">
                        <i class="fas fa-list me-2"></i>Gestionar Peticiones
                    </a>
                    <a href="index.php?accion=peticiones_eventos_admin&estado=PENDIENTE" class="btn btn-warning w-100">
                        <i class="fas fa-clock me-2"></i>Pendientes
                    </a>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 