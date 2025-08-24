<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Materia - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .subject-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
            text-align: center;
        }
        .code-preview {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-top: 15px;
            transition: all 0.3s ease;
        }
        .code-preview.active {
            border-color: var(--primary-color);
            background: rgba(102, 126, 234, 0.05);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="index.php?action=materias">
                    <i class="fas fa-book me-1"></i>Materias
                </a>
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a class="nav-link" href="index.php?action=logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Crear Nueva Materia
                </h1>
                <p class="text-muted mb-0">Agrega una nueva materia al sistema educativo</p>
            </div>
            <a href="index.php?action=materias" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a Materias
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="form-section">
                    <div class="text-center mb-4">
                        <div class="subject-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4 class="section-title">
                            <i class="fas fa-plus me-2"></i>Información de la Materia
                        </h4>
                    </div>

                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Código de la Materia -->
                        <div class="mb-4">
                            <label for="codigo" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>Código de la Materia *
                            </label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="codigo" name="codigo" 
                                   placeholder="Ej: MA, CI, ES, HI, IN" 
                                   maxlength="10" required
                                   oninput="updateCodePreview()">
                            <div class="invalid-feedback">
                                Por favor ingresa el código de la materia.
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Código único para identificar la materia (máximo 10 caracteres)
                            </div>
                            
                            <!-- Preview del código -->
                            <div class="code-preview" id="codePreview">
                                <i class="fas fa-eye me-2"></i>
                                <span id="codePreviewText">Vista previa del código</span>
                            </div>
                        </div>

                        <!-- Descripción de la Materia -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Descripción de la Materia *
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" 
                                      rows="3" placeholder="Ej: Matemáticas, Ciencias, Español, Historia, Informática" 
                                      maxlength="100" required></textarea>
                            <div class="invalid-feedback">
                                Por favor ingresa la descripción de la materia.
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nombre completo y descripción de la materia (máximo 100 caracteres)
                            </div>
                        </div>



                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Los campos marcados con * son obligatorios
                                </small>
                            </div>
                            <div>
                                <a href="index.php?action=materias" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-1"></i>Crear Materia
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Información de Ayuda -->
                <div class="form-section">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-lightbulb me-2"></i>Información de Ayuda
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-hashtag me-1"></i>Códigos Sugeridos:</h6>
                            <ul class="list-unstyled">
                                <li><code>MA</code> - Matemáticas</li>
                                <li><code>CI</code> - Ciencias</li>
                                <li><code>ES</code> - Español</li>
                                <li><code>HI</code> - Historia</li>
                                <li><code>IN</code> - Informática</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle me-1"></i>Recomendaciones:</h6>
                            <ul class="list-unstyled">
                                <li>• Usa códigos cortos y claros</li>
                                <li>• Evita espacios en el código</li>
                                <li>• Sé específico en la descripción</li>
                                <li>• Verifica que no exista el código</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Actualizar preview del código
        function updateCodePreview() {
            var codigo = document.getElementById('codigo').value;
            var preview = document.getElementById('codePreview');
            var previewText = document.getElementById('codePreviewText');
            
            if (codigo.trim() !== '') {
                preview.classList.add('active');
                previewText.textContent = 'Código: ' + codigo.toUpperCase();
            } else {
                preview.classList.remove('active');
                previewText.textContent = 'Vista previa del código';
            }
        }

        // Contador de caracteres para descripción
        document.getElementById('descripcion').addEventListener('input', function() {
            var maxLength = 100;
            var currentLength = this.value.length;
            var remaining = maxLength - currentLength;
            
            // Actualizar texto de ayuda
            var helpText = this.parentNode.querySelector('.form-text');
            if (helpText) {
                helpText.innerHTML = '<i class="fas fa-info-circle me-1"></i>' +
                    'Nombre completo y descripción de la materia (' + remaining + ' caracteres restantes)';
            }
            
            // Cambiar color si se acerca al límite
            if (remaining <= 10) {
                helpText.classList.add('text-warning');
            } else {
                helpText.classList.remove('text-warning');
            }
        });

        // Auto-capitalizar código
        document.getElementById('codigo').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Confirmar antes de cancelar
        document.querySelector('a[href="index.php?action=materias"]').addEventListener('click', function(e) {
            var form = document.querySelector('form');
            var hasData = document.getElementById('codigo').value || document.getElementById('descripcion').value;
            
            if (hasData) {
                if (!confirm('¿Estás seguro de que quieres cancelar? Se perderán los datos ingresados.')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>