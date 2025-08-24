<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                🏫 Sistema de Eventos
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?action=contacto">📞 Contacto</a>
                <a class="nav-link" href="index.php?action=login">🔐 Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container-fluid bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Bienvenido al Sistema de Eventos</h1>
                    <p class="lead">Gestiona eventos, asistencias y control de entrada de manera fácil y eficiente.</p>
                    <a href="index.php?action=login" class="btn btn-light btn-lg">
                        🚀 Comenzar
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <div class="display-1">📚</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Características -->
    <div class="container py-5">
        <h2 class="text-center mb-5">✨ Características Principales</h2>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="display-4 mb-3">📅</div>
                        <h5 class="card-title">Gestión de Eventos</h5>
                        <p class="card-text">Crea y administra eventos escolares de manera sencilla.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="display-4 mb-3">📱</div>
                        <h5 class="card-title">Códigos QR</h5>
                        <p class="card-text">Genera y escanea códigos QR para control de asistencia.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="display-4 mb-3">👥</div>
                        <h5 class="card-title">Control de Entrada</h5>
                        <p class="card-text">Registra la entrada de estudiantes a la institución.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles de Usuario -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">👥 Roles de Usuario</h2>
            
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="display-4 mb-3">👨‍💼</div>
                            <h5 class="card-title">Administrador</h5>
                            <p class="card-text">Acceso completo al sistema</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="display-4 mb-3">👨‍🏫</div>
                            <h5 class="card-title">Docente</h5>
                            <p class="card-text">Gestiona eventos y asistencias</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="display-4 mb-3">👨‍🎓</div>
                            <h5 class="card-title">Estudiante</h5>
                            <p class="card-text">Consulta eventos y asistencia</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="display-4 mb-3">👨‍👩‍👧‍👦</div>
                            <h5 class="card-title">Acudiente</h5>
                            <p class="card-text">Seguimiento de estudiantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Sistema de Eventos - I.E. Asamblea Departamental</p>
            <p class="mb-0">Desarrollado para gestión educativa</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 