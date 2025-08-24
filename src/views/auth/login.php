<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">🔐 Iniciar Sesión</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Mensaje de error -->
                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['login_error']; ?>
                                <?php unset($_SESSION['login_error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Formulario de login -->
                        <form method="POST" action="index.php?action=process-login">
                            <div class="mb-3">
                                <label for="email" class="form-label">📧 Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">🔒 Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    🚀 Ingresar
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <a href="index.php" class="btn btn-outline-secondary">
                                ← Volver al Inicio
                            </a>
                        </div>
                        
                        <!-- Información de prueba -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6>👥 Usuarios de Prueba:</h6>
                            <small class="text-muted">
                                <strong>Admin:</strong> admin@test.com / 123456<br>
                                <strong>Docente:</strong> docente@test.com / 123456<br>
                                <strong>Estudiante:</strong> estudiante@test.com / 123456<br>
                                <strong>Acudiente:</strong> acudiente@test.com / 123456
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
