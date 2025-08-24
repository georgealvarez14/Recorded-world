<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Sistema de Eventos - ESTUDIANTE
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=dashboard_estudiante">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-user-edit"></i> Mi Perfil</h2>
                        <p class="mb-0">Edita tu información personal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario de perfil -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_perfil_estudiante">
                            <input type="hidden" name="id" value="<?php echo $estudiante['id_user']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($estudiante['nom_user']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($estudiante['correo_user']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?php echo $estudiante['telef_user'] ?? ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <select class="form-select" id="ciudad" name="ciudad">
                                        <option value="">Selecciona una ciudad</option>
                                        <?php
                                        $stmt = $pdo->query("SELECT * FROM ciudad ORDER BY descripcion");
                                        $ciudades = $stmt->fetchAll();
                                        foreach ($ciudades as $ciudad) {
                                            $selected = ($estudiante['ciudad'] === $ciudad['cod_ciudad']) ? 'selected' : '';
                                            echo "<option value='{$ciudad['cod_ciudad']}' {$selected}>{$ciudad['descripcion']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Dejar vacío para no cambiar">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirmar nueva contraseña">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=dashboard_estudiante" class="btn btn-secondary me-md-2">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del acudiente -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Información del Acudiente</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_acudiente_estudiante">
                            <input type="hidden" name="id" value="<?php echo $estudiante['id_user']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom_acudiente" class="form-label">Nombre del Acudiente</label>
                                    <input type="text" class="form-control" id="nom_acudiente" name="nom_acudiente" 
                                           value="<?php echo htmlspecialchars($estudiante['nom_acudiente'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cc_acudiente" class="form-label">CC del Acudiente</label>
                                    <input type="number" class="form-control" id="cc_acudiente" name="cc_acudiente" 
                                           value="<?php echo $estudiante['cc_acudiente'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telef_acudiente" class="form-label">Teléfono del Acudiente</label>
                                    <input type="tel" class="form-control" id="telef_acudiente" name="telef_acudiente" 
                                           value="<?php echo $estudiante['telef_acudiente'] ?? ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telef_acudiente_dos" class="form-label">Teléfono Alternativo</label>
                                    <input type="tel" class="form-control" id="telef_acudiente_dos" name="telef_acudiente_dos" 
                                           value="<?php echo $estudiante['telef_acudiente_dos'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="correo_acudiente" class="form-label">Email del Acudiente</label>
                                <input type="email" class="form-control" id="correo_acudiente" name="correo_acudiente" 
                                       value="<?php echo htmlspecialchars($estudiante['correo_acudiente'] ?? ''); ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-save"></i> Actualizar Acudiente
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información académica -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-id-card"></i> ID Estudiante:</strong> <?php echo $estudiante['id_user']; ?></p>
                                <p><strong><i class="fas fa-graduation-cap"></i> Grado Actual:</strong> 
                                    <?php echo $estudiante['nombre_grado'] ?? 'No asignado'; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-users"></i> Grupo:</strong> <?php echo $estudiante['grupo'] ?? 'No asignado'; ?></p>
                                <p><strong><i class="fas fa-calendar"></i> Fecha de Registro:</strong> 
                                    <?php echo $estudiante['fecha_creacion'] ? date('d/m/Y', strtotime($estudiante['fecha_creacion'])) : 'No registrada'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=dashboard_estudiante" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        // Validar que las contraseñas coincidan
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
        
        document.getElementById('password').addEventListener('input', function() {
            const confirmPassword = document.getElementById('confirm_password');
            if (confirmPassword.value) {
                if (this.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 