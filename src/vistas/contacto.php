<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">🏠 Inicio</a>
                <a class="nav-link" href="index.php?accion=login">🔐 Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">📞 Contáctenos</h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Información de contacto -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>📧 Información de Contacto</h5>
                                <p><strong>Email:</strong> info@institucion.edu.co</p>
                                <p><strong>Teléfono:</strong> (123) 456-7890</p>
                                <p><strong>Dirección:</strong> Calle Principal #123</p>
                            </div>
                            <div class="col-md-6">
                                <h5>🕒 Horarios de Atención</h5>
                                <p><strong>Lunes a Viernes:</strong> 7:00 AM - 5:00 PM</p>
                                <p><strong>Sábados:</strong> 8:00 AM - 12:00 PM</p>
                            </div>
                        </div>

                        <!-- Formulario de contacto -->
                        <form method="POST" action="#">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto *</label>
                                <select class="form-select" id="asunto" name="asunto" required>
                                    <option value="">Selecciona un asunto</option>
                                    <option value="Información General">Información General</option>
                                    <option value="Inscripciones">Inscripciones</option>
                                    <option value="Eventos">Eventos</option>
                                    <option value="Problemas Técnicos">Problemas Técnicos</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje *</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" 
                                          placeholder="Escribe tu mensaje aquí..." required></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    📤 Enviar Mensaje
                                </button>
                            </div>
                        </form>
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