<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="user-avatar me-3">
                            <?php if (!empty($_SESSION['user_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['user_photo']); ?>" alt="Foto de perfil" class="user-avatar">
                            <?php else: ?>
                            <i class="fas fa-user-circle fa-2x text-white"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h6 class="text-white mb-0"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h6>
                            <small class="text-white-50">Administrador</small>
                        </div>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        
                        <!-- Gestión de Personas -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Gestión de Personas</h6>
                            <a class="nav-link" href="index.php?action=personas">
                                <i class="fas fa-users me-2"></i> Ver Todas las Personas
                            </a>
                            <a class="nav-link" href="index.php?action=personas&controller=create">
                                <i class="fas fa-user-plus me-2"></i> Crear Nueva Persona
                            </a>
                            <a class="nav-link" href="index.php?action=personas&controller=list">
                                <i class="fas fa-list me-2"></i> Lista de Personas
                            </a>
                        </div>
                        
                        <!-- Gestión de Eventos -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Gestión de Eventos</h6>
                            <a class="nav-link" href="index.php?action=eventos">
                                <i class="fas fa-calendar-alt me-2"></i> Ver Todos los Eventos
                            </a>
                            <a class="nav-link" href="index.php?action=eventos&controller=create">
                                <i class="fas fa-calendar-plus me-2"></i> Crear Nuevo Evento
                            </a>
                            <a class="nav-link" href="index.php?action=eventos&controller=list">
                                <i class="fas fa-list me-2"></i> Lista de Eventos
                            </a>
                        </div>
                        
                        <!-- Gestión de Materias -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Gestión de Materias</h6>
                            <a class="nav-link" href="index.php?action=materias">
                                <i class="fas fa-book me-2"></i> Ver Todas las Materias
                            </a>
                            <a class="nav-link" href="index.php?action=materias&controller=create">
                                <i class="fas fa-plus me-2"></i> Crear Nueva Materia
                            </a>
                            <a class="nav-link" href="index.php?action=materias&controller=list">
                                <i class="fas fa-list me-2"></i> Lista de Materias
                            </a>
                        </div>
                        
                        <!-- Gestión de Participantes -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Participantes</h6>
                            <a class="nav-link" href="index.php?action=participantes">
                                <i class="fas fa-user-check me-2"></i> Ver Participantes
                            </a>
                            <a class="nav-link" href="index.php?action=participantes&controller=registrar">
                                <i class="fas fa-user-plus me-2"></i> Registrar Participante
                            </a>
                            <a class="nav-link" href="index.php?action=participantes&controller=list">
                                <i class="fas fa-list me-2"></i> Lista de Participantes
                            </a>
                        </div>
                        
                        <!-- Asignación de Profesores -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Asignaciones</h6>
                            <a class="nav-link" href="index.php?action=asignar-profesor">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Asignar Profesores
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-user-tie me-2"></i> Gestionar Cargos
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-tasks me-2"></i> Ver Asignaciones
                            </a>
                        </div>
                        
                        <!-- Botón Prominente de Control de Entrada -->
                        <hr class="text-white-50">
                        <div class="text-center mb-3">
                            <a class="btn btn-success btn-lg w-100" href="index.php?action=control-entrada" 
                               style="font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
                                <i class="fas fa-door-open me-2"></i>CONTROL DE ENTRADA
                            </a>
                        </div>
                        
                        <!-- Botón de Asistencia a Eventos -->
                        <div class="text-center mb-3">
                            <a class="btn btn-primary btn-lg w-100" href="index.php?action=registrar-asistencia" 
                               style="font-weight: bold; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);">
                                <i class="fas fa-camera me-2"></i>ASISTENCIA EVENTOS
                            </a>
                        </div>
                        
                        <!-- Botón de Gestión de QR -->
                        <div class="text-center mb-3">
                            <a class="btn btn-info btn-lg w-100" href="index.php?action=gestionar-qr" 
                               style="font-weight: bold; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);">
                                <i class="fas fa-qrcode me-2"></i>GESTIONAR QR
                            </a>
                        </div>
                        
                        <!-- Reportes y Estadísticas -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Reportes</h6>
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-bar me-2"></i> Estadísticas Generales
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-file-alt me-2"></i> Reporte de Asistencia
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-download me-2"></i> Exportar Datos
                            </a>
                        </div>
                        
                        <!-- Configuración del Sistema -->
                        <div class="nav-section mb-3">
                            <h6 class="text-white-50 text-uppercase small mb-2">Configuración</h6>
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog me-2"></i> Configuración General
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-users-cog me-2"></i> Gestión de Roles
                            </a>
                            <a class="nav-link" href="#">
                                <i class="fas fa-database me-2"></i> Respaldo de BD
                            </a>
                        </div>
                        
                        <hr class="text-white-50">
                        <a class="nav-link" href="index.php?action=logout">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <!-- Welcome Section -->
                    <div class="welcome-section">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="mb-2 text-white">
                                    <i class="fas fa-crown me-2"></i>
                                    Panel de Administración
                                </h1>
                                <p class="mb-0 text-white opacity-75">
                                    Control total del sistema de eventos educativos
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="me-3 text-white">
                                        <h4 class="mb-0"><?php echo date('d'); ?></h4>
                                        <small><?php echo date('M Y'); ?></small>
                                    </div>
                                    <i class="fas fa-shield-alt fa-3x text-white opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Access Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-primary-gradient me-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Personas</h6>
                                            <a href="index.php?action=personas" class="btn btn-sm btn-outline-primary">
                                                Gestionar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-success-gradient me-3">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Eventos</h6>
                                            <a href="index.php?action=eventos" class="btn btn-sm btn-outline-success">
                                                Gestionar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-warning-gradient me-3">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Materias</h6>
                                            <a href="index.php?action=materias" class="btn btn-sm btn-outline-warning">
                                                Gestionar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-info-gradient me-3">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Participantes</h6>
                                            <a href="index.php?action=participantes" class="btn btn-sm btn-outline-info">
                                                Gestionar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card card-stats" style="border: 3px solid var(--color-exito) !important; box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-success-gradient me-3">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-success fw-bold">Registrar Asistencia</h6>
                                            <a href="index.php?action=participantes&controller=registrar" class="btn btn-success btn-sm">
                                                <i class="fas fa-clipboard-check me-1"></i>REGISTRAR
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admin Actions Grid -->
                    <div class="row">
                        <div class="col-12">
                            <div class="recent-events p-4">
                                <h4 class="mb-4">
                                    <i class="fas fa-tools me-2 text-primary"></i>
                                    Acciones de Administración
                                </h4>
                                
                                <div class="row">
                                    <!-- Gestión de Personas -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-users me-2"></i>Gestión de Personas
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="index.php?action=personas" class="text-decoration-none">
                                                        <i class="fas fa-list me-2"></i>Ver todas las personas
                                                    </a></li>
                                                    <li><a href="index.php?action=personas&controller=create" class="text-decoration-none">
                                                        <i class="fas fa-user-plus me-2"></i>Crear nueva persona
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-edit me-2"></i>Editar personas existentes
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-trash me-2"></i>Eliminar personas
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Gestión de Eventos -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-calendar-alt me-2"></i>Gestión de Eventos
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="index.php?action=eventos" class="text-decoration-none">
                                                        <i class="fas fa-list me-2"></i>Ver todos los eventos
                                                    </a></li>
                                                    <li><a href="index.php?action=eventos&controller=create" class="text-decoration-none">
                                                        <i class="fas fa-calendar-plus me-2"></i>Crear nuevo evento
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-edit me-2"></i>Editar eventos
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-trash me-2"></i>Eliminar eventos
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Gestión de Materias -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-book me-2"></i>Gestión de Materias
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="index.php?action=materias" class="text-decoration-none">
                                                        <i class="fas fa-list me-2"></i>Ver todas las materias
                                                    </a></li>
                                                    <li><a href="index.php?action=materias&controller=create" class="text-decoration-none">
                                                        <i class="fas fa-plus me-2"></i>Crear nueva materia
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-edit me-2"></i>Editar materias
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-trash me-2"></i>Eliminar materias
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Gestión de Participantes -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-user-check me-2"></i>Participantes
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="index.php?action=participantes" class="text-decoration-none">
                                                        <i class="fas fa-list me-2"></i>Ver participantes
                                                    </a></li>
                                                    <li><a href="index.php?action=participantes&controller=registrar" class="text-decoration-none">
                                                        <i class="fas fa-user-plus me-2"></i>Registrar participante
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-clipboard-check me-2"></i>Control de asistencia
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reportes -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-chart-bar me-2"></i>Reportes
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-chart-line me-2"></i>Estadísticas generales
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-file-alt me-2"></i>Reporte de asistencia
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-download me-2"></i>Exportar datos
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-print me-2"></i>Imprimir reportes
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Configuración -->
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-cog me-2"></i>Configuración
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-cogs me-2"></i>Configuración general
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-users-cog me-2"></i>Gestión de roles
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-database me-2"></i>Respaldo de BD
                                                    </a></li>
                                                    <li><a href="#" class="text-decoration-none">
                                                        <i class="fas fa-shield-alt me-2"></i>Seguridad
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Marcar enlace activo
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 