<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Eventos - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt me-2"></i>Sistema de Eventos
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="#caracteristicas">
                    <i class="fas fa-star me-1"></i>Características
                </a>
                <a class="nav-link" href="#acerca">
                    <i class="fas fa-info-circle me-1"></i>Acerca de
                </a>
                <a class="nav-link" href="index.php?accion=login">
                    <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        🎯 Sistema de Gestión de Eventos
                    </h1>
                    <p class="lead mb-4">
                        Gestiona eventos escolares, controla asistencias y administra participantes 
                        de manera eficiente y profesional.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="index.php?accion=login" class="btn btn-light btn-lg">
                            <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                        </a>
                        <a href="#caracteristicas" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-play me-2"></i>Ver Más
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-calendar-check" style="font-size: 8rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas Rápidas -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-users text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2">Gestión de Personas</h4>
                        <p class="text-muted">Estudiantes, docentes y acudientes</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-calendar-alt text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2">Eventos Escolares</h4>
                        <p class="text-muted">Organización y control total</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-qrcode text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2">Códigos QR</h4>
                        <p class="text-muted">Identificación rápida y segura</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-chart-bar text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2">Reportes</h4>
                        <p class="text-muted">Estadísticas y análisis</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Características Principales -->
    <section id="caracteristicas" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">✨ Características Principales</h2>
                    <p class="lead text-muted">Descubre todo lo que nuestro sistema puede hacer por ti</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-user-graduate text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Gestión de Personas</h5>
                            <p class="card-text">
                                Administra estudiantes, docentes, acudientes y administradores 
                                con información completa y organizada.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Registro completo de datos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Información académica</li>
                                <li><i class="fas fa-check text-success me-2"></i>Datos de contacto</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-calendar-plus text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Organización de Eventos</h5>
                            <p class="card-text">
                                Crea y gestiona eventos escolares con fechas, horarios, 
                                ubicaciones y descripciones detalladas.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Calendario de eventos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Gestión de horarios</li>
                                <li><i class="fas fa-check text-success me-2"></i>Control de ubicaciones</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-qrcode text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Códigos QR Inteligentes</h5>
                            <p class="card-text">
                                Genera códigos QR únicos para cada persona y evento 
                                para identificación rápida y control de acceso.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>QR personalizados</li>
                                <li><i class="fas fa-check text-success me-2"></i>Generación masiva</li>
                                <li><i class="fas fa-check text-success me-2"></i>Descarga fácil</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-clipboard-check text-info" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Control de Asistencia</h5>
                            <p class="card-text">
                                Registra y controla la asistencia a eventos con 
                                precisión y facilidad de gestión.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Registro automático</li>
                                <li><i class="fas fa-check text-success me-2"></i>Reportes de asistencia</li>
                                <li><i class="fas fa-check text-success me-2"></i>Estadísticas en tiempo real</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-chart-line text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Reportes y Estadísticas</h5>
                            <p class="card-text">
                                Obtén reportes detallados y estadísticas sobre 
                                eventos, asistencias y participación.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Gráficos interactivos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Exportación de datos</li>
                                <li><i class="fas fa-check text-success me-2"></i>Análisis de tendencias</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-shield-alt text-secondary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Seguridad y Acceso</h5>
                            <p class="card-text">
                                Sistema seguro con diferentes niveles de acceso 
                                según el tipo de usuario y responsabilidades.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Roles de usuario</li>
                                <li><i class="fas fa-check text-success me-2"></i>Acceso controlado</li>
                                <li><i class="fas fa-check text-success me-2"></i>Datos protegidos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Usuario -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">👥 Tipos de Usuario</h2>
                    <p class="lead text-muted">Cada usuario tiene funciones específicas según su rol</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="user-type-icon mb-3">
                            <i class="fas fa-user-shield text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h5>Administrador</h5>
                        <p class="text-muted">Control total del sistema</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-cog text-primary me-2"></i>Gestión completa</li>
                            <li><i class="fas fa-users text-primary me-2"></i>Administrar usuarios</li>
                            <li><i class="fas fa-chart-bar text-primary me-2"></i>Reportes avanzados</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="user-type-icon mb-3">
                            <i class="fas fa-chalkboard-teacher text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5>Docente</h5>
                        <p class="text-muted">Gestión académica</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-calendar text-success me-2"></i>Ver eventos</li>
                            <li><i class="fas fa-user-graduate text-success me-2"></i>Gestionar estudiantes</li>
                            <li><i class="fas fa-clipboard-list text-success me-2"></i>Control asistencia</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="user-type-icon mb-3">
                            <i class="fas fa-user-graduate text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h5>Estudiante</h5>
                        <p class="text-muted">Participación en eventos</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-calendar-check text-warning me-2"></i>Ver eventos</li>
                            <li><i class="fas fa-qrcode text-warning me-2"></i>QR personal</li>
                            <li><i class="fas fa-history text-warning me-2"></i>Historial personal</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="user-type-icon mb-3">
                            <i class="fas fa-user-friends text-info" style="font-size:4rem;"></i>
                        </div>
                        <h5>Acudiente</h5>
                        <p class="text-muted">Seguimiento familiar</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-eye text-info me-2"></i>Ver información</li>
                            <li><i class="fas fa-calendar-alt text-info me-2"></i>Eventos familiares</li>
                            <li><i class="fas fa-bell text-info me-2"></i>Notificaciones</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Acerca del Sistema -->
    <section id="acerca" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">📚 Acerca del Sistema</h2>
                    <p class="lead mb-4">
                        Este sistema de gestión de eventos fue desarrollado como proyecto de graduación 
                        para facilitar la administración de eventos escolares y el control de asistencias.
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-check-circle text-success me-2"></i>Fácil de usar</h6>
                            <h6><i class="fas fa-check-circle text-success me-2"></i>Interfaz intuitiva</h6>
                        </div>
                        <div class="col-6">
                            <h6><i class="fas fa-check-circle text-success me-2"></i>Rápido y eficiente</h6>
                            <h6><i class="fas fa-check-circle text-success me-2"></i>Totalmente funcional</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="about-image">
                        <i class="fas fa-graduation-cap" style="font-size: 8rem; color: #007bff;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">🚀 ¿Listo para comenzar?</h2>
            <p class="lead mb-4">
                Únete a nuestro sistema y comienza a gestionar eventos de manera profesional
            </p>
            <a href="index.php?accion=login" class="btn btn-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Sistema de Gestión de Eventos</h5>
                    <p class="mb-0">Proyecto de graduación - Gestión eficiente de eventos escolares</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-code me-2"></i>Desarrollado con PHP, MySQL y Bootstrap
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar me-2"></i>© 2024 - Todos los derechos reservados
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        .hero-image {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .feature-icon {
            transition: transform 0.3s ease;
        }
        
        .card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .user-type-icon {
            transition: transform 0.3s ease;
        }
        
        .user-type-icon:hover {
            transform: scale(1.1);
        }
        
        .stat-item {
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
    </style>
</body>
</html> 