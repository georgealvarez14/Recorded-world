<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos</a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    👤 Bienvenido, <?php echo htmlspecialchars($auth->getUserName()); ?>
                </span>
                <a class="nav-link" href="index.php?action=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Mensaje de bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2>¡Bienvenido al Sistema de Eventos!</h2>
                        <p class="mb-0">Gestiona eventos, asistencias y control de entrada</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones principales -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 mb-3">📅</div>
                        <h5 class="card-title">Eventos</h5>
                        <p class="card-text">Ver y gestionar eventos</p>
                        <a href="index.php?action=eventos" class="btn btn-primary">
                            Ver Eventos
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 mb-3">👥</div>
                        <h5 class="card-title">Personas</h5>
                        <p class="card-text">Gestionar estudiantes y docentes</p>
                        <a href="index.php?action=personas" class="btn btn-primary">
                            Ver Personas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones específicos por rol -->
        <?php if ($auth->isAdmin()): ?>
            <!-- Botones para administradores -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">📱</div>
                            <h5 class="card-title">Gestionar QR</h5>
                            <p class="card-text">Generar y administrar códigos QR</p>
                            <a href="index.php?action=admin-qr" class="btn btn-warning">
                                Gestionar QR
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">🚪</div>
                            <h5 class="card-title">Control de Entrada</h5>
                            <p class="card-text">Registrar entrada de estudiantes</p>
                            <a href="index.php?action=control-entrada" class="btn btn-success">
                                Control de Entrada
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($auth->isTeacher()): ?>
            <!-- Botones para docentes -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">✅</div>
                            <h5 class="card-title">Registrar Asistencia</h5>
                            <p class="card-text">Tomar asistencia a eventos</p>
                            <a href="index.php?action=registrar-asistencia" class="btn btn-info">
                                Registrar Asistencia
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Información del usuario -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">👤 Información del Usuario</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($auth->getUserName()); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                        <p><strong>Rol:</strong> 
                            <?php 
                            $roles = [
                                'ADM' => 'Administrador',
                                'DOC' => 'Docente', 
                                'EST' => 'Estudiante',
                                'ACU' => 'Acudiente'
                            ];
                            echo $roles[$auth->getUserType()] ?? 'Usuario';
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📊 Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?action=eventos" class="btn btn-outline-primary">
                                📅 Ver Eventos
                            </a>
                            <a href="index.php?action=personas" class="btn btn-outline-secondary">
                                👥 Ver Personas
                            </a>
                            <a href="index.php?action=contacto" class="btn btn-outline-info">
                                📞 Contacto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2024 Sistema de Eventos - I.E. Asamblea Departamental</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 