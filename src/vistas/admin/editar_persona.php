<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Persona - Administrador</title>
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user-edit me-2"></i>Editar Persona</h1>
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
        <form method="POST" action="index.php?accion=actualizar_persona_admin" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $persona['id_user']; ?>">
            
            <!-- Información Personal -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-circle me-2"></i>Información Personal
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label required-field">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($persona['nom_user']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($persona['correo_user']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars($persona['telef_user'] ?? ''); ?>">
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
                                    $selected = ($persona['ciudad'] === $ciudad['cod_ciudad']) ? 'selected' : '';
                                    echo "<option value='{$ciudad['cod_ciudad']}' {$selected}>{$ciudad['descripcion']}</option>";
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
                                    $selected = ($persona['tipo_persona'] === $tipo['cod_tipo']) ? 'selected' : '';
                                    echo "<option value='{$tipo['cod_tipo']}' {$selected}>{$tipo['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Dejar vacío para no cambiar">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <?php if (isset($persona['foto_persona']) && $persona['foto_persona']): ?>
                                <small class="text-muted">Foto actual: <?php echo basename($persona['foto_persona']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Código QR -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-qrcode me-2"></i>Gestión de Código QR
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado del QR</label>
                            <div class="d-flex align-items-center">
                                <?php if (isset($persona['codigo_qr']) && $persona['codigo_qr'] && file_exists($persona['codigo_qr'])): ?>
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-check-circle me-1"></i>QR Generado
                                    </span>
                                    <small class="text-muted">Archivo: <?php echo basename($persona['codigo_qr']); ?></small>
                                <?php else: ?>
                                    <span class="badge bg-warning me-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Sin QR
                                    </span>
                                    <small class="text-muted">No se ha generado código QR</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Acciones QR</label>
                            <div class="d-flex gap-2">
                                <?php if (isset($persona['codigo_qr']) && $persona['codigo_qr'] && file_exists($persona['codigo_qr'])): ?>
                                    <a href="<?php echo $persona['codigo_qr']; ?>" 
                                       class="btn btn-outline-primary btn-sm" 
                                       target="_blank"
                                       title="Ver QR">
                                        <i class="fas fa-eye me-1"></i>Ver QR
                                    </a>
                                    <a href="<?php echo $persona['codigo_qr']; ?>" 
                                       class="btn btn-outline-success btn-sm" 
                                       target="_blank"
                                       download="<?php echo basename($persona['codigo_qr']); ?>"
                                       title="Descargar QR">
                                        <i class="fas fa-download me-1"></i>Descargar
                                    </a>
                                    <a href="index.php?accion=eliminar_qr_persona&id=<?php echo $persona['id_user']; ?>" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('¿Estás seguro de eliminar este QR?')"
                                       title="Eliminar QR">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?accion=generar_qr_persona&id=<?php echo $persona['id_user']; ?>" 
                                       class="btn btn-primary btn-sm"
                                       title="Generar nuevo QR">
                                        <i class="fas fa-qrcode me-1"></i>Generar QR
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($persona['codigo_qr']) && $persona['codigo_qr'] && file_exists($persona['codigo_qr'])): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Información del QR:</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Fecha de generación:</strong><br>
                                        <small><?php echo date('d/m/Y H:i:s', filemtime($persona['codigo_qr'])); ?></small>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Tamaño del archivo:</strong><br>
                                        <small><?php echo number_format(filesize($persona['codigo_qr']) / 1024, 2); ?> KB</small>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Ubicación:</strong><br>
                                        <small><code><?php echo $persona['codigo_qr']; ?></code></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Campos específicos para estudiantes -->
            <div id="campos_estudiante" class="card" style="display: <?php echo $persona['tipo_persona'] === 'EST' ? 'block' : 'none'; ?>;">
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
                                    $selected = ($persona['cod_grado'] == $grado['cod_grado']) ? 'selected' : '';
                                    echo "<option value='{$grado['cod_grado']}' {$selected}>{$grado['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grupo" class="form-label">Grupo</label>
                            <select class="form-select" id="grupo" name="grupo">
                                <option value="">Selecciona un grupo</option>
                                <option value="1" <?php echo $persona['grupo'] == 1 ? 'selected' : ''; ?>>Grupo 1</option>
                                <option value="2" <?php echo $persona['grupo'] == 2 ? 'selected' : ''; ?>>Grupo 2</option>
                                <option value="3" <?php echo $persona['grupo'] == 3 ? 'selected' : ''; ?>>Grupo 3</option>
                                <option value="4" <?php echo $persona['grupo'] == 4 ? 'selected' : ''; ?>>Grupo 4</option>
                                <option value="5" <?php echo $persona['grupo'] == 5 ? 'selected' : ''; ?>>Grupo 5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Acudiente (solo para estudiantes) -->
            <div id="campos_acudiente" class="card" style="display: <?php echo $persona['tipo_persona'] === 'EST' ? 'block' : 'none'; ?>;">
                <div class="card-header">
                    <i class="fas fa-user-friends me-2"></i>Información del Acudiente
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_acudiente" class="form-label">Nombre del Acudiente</label>
                            <input type="text" class="form-control" id="nom_acudiente" name="nom_acudiente" 
                                   value="<?php echo htmlspecialchars($persona['nom_acudiente'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc_acudiente" class="form-label">CC del Acudiente</label>
                            <input type="text" class="form-control" id="cc_acudiente" name="cc_acudiente" 
                                   value="<?php echo htmlspecialchars($persona['cc_acudiente'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telef_acudiente" class="form-label">Teléfono Principal del Acudiente</label>
                            <input type="tel" class="form-control" id="telef_acudiente" name="telef_acudiente" 
                                   value="<?php echo htmlspecialchars($persona['telef_acudiente'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telef_acudiente_dos" class="form-label">Teléfono Secundario del Acudiente</label>
                            <input type="tel" class="form-control" id="telef_acudiente_dos" name="telef_acudiente_dos" 
                                   value="<?php echo htmlspecialchars($persona['telef_acudiente_dos'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo_acudiente" class="form-label">Email del Acudiente</label>
                            <input type="email" class="form-control" id="correo_acudiente" name="correo_acudiente" 
                                   value="<?php echo htmlspecialchars($persona['correo_acudiente'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="text-center mt-4 mb-4">
                <a href="index.php?accion=crud_personas" class="btn btn-secondary btn-action me-3">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-action">
                    <i class="fas fa-save me-2"></i>Actualizar Persona
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
            
            // Ocultar todos los campos adicionales
            camposEstudiante.style.display = 'none';
            camposAcudiente.style.display = 'none';
            
            // Mostrar campos según el tipo seleccionado
            if (tipo === 'EST') {
                camposEstudiante.style.display = 'block';
                camposAcudiente.style.display = 'block';
            }
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const tipo = document.getElementById('tipo').value;
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            
            if (!nombre || !email || !tipo) {
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
        });
    </script>
</body>
</html> 