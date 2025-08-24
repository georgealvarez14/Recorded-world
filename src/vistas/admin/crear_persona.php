<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Persona - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-header {
            background: #343a40;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: #495057;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px 20px;
            font-weight: 500;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
        }

        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
        }

        .section-divider {
            border-top: 2px solid #dee2e6;
            margin: 30px 0;
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user-plus me-2"></i>Crear Nueva Persona</h1>
                <div>
                    <a href="index.php?accion=crud_personas" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <a href="index.php?accion=inicio" class="btn btn-outline-light btn-sm ms-2">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <form method="POST" action="index.php?accion=guardar_persona_admin" enctype="multipart/form-data">
            
            <!-- Información Personal -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-circle me-2"></i>Información Personal
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label required-field">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="help-text">Ingresa el nombre completo de la persona</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="help-text">Será usado para el inicio de sesión</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono">
                            <div class="help-text">Número de contacto principal</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <select class="form-select" id="ciudad" name="ciudad">
                                <option value="">Selecciona una ciudad</option>
                                <?php
                                $stmt = $pdo->prepare("SELECT cod_ciudad, descripcion FROM ciudad ORDER BY descripcion");
                                $stmt->execute();
                                $ciudades = $stmt->fetchAll();
                                foreach ($ciudades as $ciudad) {
                                    echo "<option value='{$ciudad['cod_ciudad']}'>{$ciudad['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label required-field">Tipo de Persona</label>
                            <select class="form-select" id="tipo" name="tipo" required onchange="mostrarCamposAdicionales()">
                                <option value="">Selecciona un tipo</option>
                                <?php
                                $stmt = $pdo->prepare("SELECT cod_tipo, descripcion FROM tipo_persona ORDER BY descripcion");
                                $stmt->execute();
                                $tipos = $stmt->fetchAll();
                                foreach ($tipos as $tipo) {
                                    echo "<option value='{$tipo['cod_tipo']}'>{$tipo['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label required-field">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="help-text">Mínimo 6 caracteres</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="help-text">Formatos: JPG, PNG, GIF. Máximo 2MB</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campos específicos para estudiantes -->
            <div id="campos_estudiante" class="card" style="display: none;">
                <div class="card-header">
                    <i class="fas fa-graduation-cap me-2"></i>Información Académica
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="grado" class="form-label">Grado</label>
                            <select class="form-select" id="grado" name="grado">
                                <option value="">Selecciona un grado</option>
                                <?php
                                $stmt = $pdo->prepare("SELECT cod_grado, descripcion FROM grado ORDER BY cod_grado");
                                $stmt->execute();
                                $grados = $stmt->fetchAll();
                                foreach ($grados as $grado) {
                                    echo "<option value='{$grado['cod_grado']}'>{$grado['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grupo" class="form-label">Grupo</label>
                            <select class="form-select" id="grupo" name="grupo">
                                <option value="">Selecciona un grupo</option>
                                <option value="1">Grupo 1</option>
                                <option value="2">Grupo 2</option>
                                <option value="3">Grupo 3</option>
                                <option value="4">Grupo 4</option>
                                <option value="5">Grupo 5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Acudiente (solo para estudiantes) -->
            <div id="campos_acudiente" class="card" style="display: none;">
                <div class="card-header">
                    <i class="fas fa-user-friends me-2"></i>Información del Acudiente
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_acudiente" class="form-label">Nombre del Acudiente</label>
                            <input type="text" class="form-control" id="nom_acudiente" name="nom_acudiente">
                            <div class="help-text">Nombre completo del padre, madre o tutor</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc_acudiente" class="form-label">CC del Acudiente</label>
                            <input type="text" class="form-control" id="cc_acudiente" name="cc_acudiente">
                            <div class="help-text">Número de identificación</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telef_acudiente" class="form-label">Teléfono Principal del Acudiente</label>
                            <input type="tel" class="form-control" id="telef_acudiente" name="telef_acudiente">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telef_acudiente_dos" class="form-label">Teléfono Secundario del Acudiente</label>
                            <input type="tel" class="form-control" id="telef_acudiente_dos" name="telef_acudiente_dos">
                            <div class="help-text">Teléfono alternativo para emergencias</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo_acudiente" class="form-label">Email del Acudiente</label>
                            <input type="email" class="form-control" id="correo_acudiente" name="correo_acudiente">
                            <div class="help-text">Para notificaciones y comunicaciones</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional para docentes -->
            <div id="campos_docente" class="card" style="display: none;">
                <div class="card-header">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Información Profesional
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                   placeholder="Matemáticas, Ciencias, Español, etc.">
                            <div class="help-text">Área de especialización del docente</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono_docente" class="form-label">Teléfono Profesional</label>
                            <input type="tel" class="form-control" id="telefono_docente" name="telefono_docente">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional para acudientes -->
            <div id="campos_acudiente_info" class="card" style="display: none;">
                <div class="card-header">
                    <i class="fas fa-user-tie me-2"></i>Información de Contacto
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono_acudiente" class="form-label">Teléfono de Contacto</label>
                            <input type="tel" class="form-control" id="telefono_acudiente" name="telefono_acudiente">
                            <div class="help-text">Para comunicaciones urgentes</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parentesco" class="form-label">Parentesco</label>
                            <select class="form-select" id="parentesco" name="parentesco">
                                <option value="">Selecciona el parentesco</option>
                                <option value="padre">Padre</option>
                                <option value="madre">Madre</option>
                                <option value="tutor">Tutor</option>
                                <option value="abuelo">Abuelo/a</option>
                                <option value="hermano">Hermano/a</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="text-center mt-4 mb-4">
                <a href="index.php?accion=crud_personas" class="btn btn-secondary btn-action me-3">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-action">
                    <i class="fas fa-save me-2"></i>Crear Persona
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mostrarCamposAdicionales() {
            const tipo = document.getElementById('tipo').value;
            const camposEstudiante = document.getElementById('campos_estudiante');
            const camposAcudiente = document.getElementById('campos_acudiente');
            const camposDocente = document.getElementById('campos_docente');
            const camposAcudienteInfo = document.getElementById('campos_acudiente_info');
            
            // Ocultar todos los campos adicionales
            camposEstudiante.style.display = 'none';
            camposAcudiente.style.display = 'none';
            camposDocente.style.display = 'none';
            camposAcudienteInfo.style.display = 'none';
            
            // Mostrar campos según el tipo seleccionado
            if (tipo === 'EST') {
                camposEstudiante.style.display = 'block';
                camposAcudiente.style.display = 'block';
            } else if (tipo === 'DOC') {
                camposDocente.style.display = 'block';
            } else if (tipo === 'ACU') {
                camposAcudienteInfo.style.display = 'block';
            }
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const tipo = document.getElementById('tipo').value;
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Validar campos obligatorios
            if (!nombre || !email || !tipo || !password) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
                return false;
            }
            
            // Validación de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor, ingresa un email válido.');
                return false;
            }
            
            // Validación de contraseña
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres.');
                return false;
            }
            
            // Validación específica para estudiantes
            if (tipo === 'EST') {
                const grado = document.getElementById('grado').value;
                const nomAcudiente = document.getElementById('nom_acudiente').value;
                
                if (!grado) {
                    e.preventDefault();
                    alert('Para estudiantes, es obligatorio seleccionar un grado.');
                    return false;
                }
                
                if (!nomAcudiente) {
                    e.preventDefault();
                    alert('Para estudiantes, es obligatorio ingresar el nombre del acudiente.');
                    return false;
                }
            }
        });

        // Validación de archivo de imagen
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tamaño (2MB máximo)
                if (file.size > 2 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 2MB permitido.');
                    this.value = '';
                    return;
                }
                
                // Validar tipo de archivo
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Solo se permiten archivos de imagen (JPG, PNG, GIF).');
                    this.value = '';
                    return;
                }
            }
        });
    </script>
</body>
</html> 