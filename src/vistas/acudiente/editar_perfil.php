<?php
// Verificar que el usuario sea acudiente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir el controlador de acudientes
require_once '../src/controllers/AcudienteController.php';
$acudienteController = new AcudienteController();

// Obtener información del acudiente
$acudiente = $acudienteController->getInformacionAcudiente($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-warning">
        <div class="container">
            <a class="navbar-brand" href="index.php?accion=dashboard_acudiente">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                </span>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Mensaje de bienvenida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-user-edit"></i> Editar Mi Perfil</h2>
                        <p class="mb-0">Actualiza tu información personal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario de edición -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_perfil_acudiente">
                            
                            <!-- Información básica -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nom_user" class="form-label">
                                        <i class="fas fa-user"></i> Nombre Completo
                                    </label>
                                    <input type="text" class="form-control" id="nom_user" name="nom_user" 
                                           value="<?php echo htmlspecialchars($acudiente['nom_user'] ?? $_SESSION['usuario_nombre']); ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="correo_user" class="form-label">
                                        <i class="fas fa-envelope"></i> Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" id="correo_user" name="correo_user" 
                                           value="<?php echo htmlspecialchars($acudiente['correo_user'] ?? $_SESSION['usuario_email']); ?>" 
                                           required>
                                </div>
                            </div>

                            <!-- Información de contacto -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="telef_user" class="form-label">
                                        <i class="fas fa-phone"></i> Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="telef_user" name="telef_user" 
                                           value="<?php echo $acudiente['telef_user'] ?? ''; ?>" 
                                           placeholder="Ej: 3001234567">
                                </div>
                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Ciudad
                                    </label>
                                    <select class="form-select" id="ciudad" name="ciudad">
                                        <option value="">Seleccionar ciudad</option>
                                        <?php
                                        // Obtener ciudades de la base de datos
                                        try {
                                            $stmt = $pdo->prepare("SELECT cod_ciudad, descripcion FROM ciudad ORDER BY descripcion");
                                            $stmt->execute();
                                            $ciudades = $stmt->fetchAll();
                                            
                                            foreach ($ciudades as $ciudad) {
                                                $selected = ($ciudad['cod_ciudad'] == ($acudiente['ciudad'] ?? '')) ? 'selected' : '';
                                                echo "<option value='{$ciudad['cod_ciudad']}' {$selected}>{$ciudad['descripcion']}</option>";
                                            }
                                        } catch (Exception $e) {
                                            echo "<option value=''>Error al cargar ciudades</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Información de seguridad -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="contrasena_actual" class="form-label">
                                        <i class="fas fa-lock"></i> Contraseña Actual
                                    </label>
                                    <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" 
                                           placeholder="Ingresa tu contraseña actual">
                                    <div class="form-text">Solo si deseas cambiar la contraseña</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nueva_contrasena" class="form-label">
                                        <i class="fas fa-key"></i> Nueva Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" 
                                           placeholder="Nueva contraseña (opcional)">
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                            </div>

                            <!-- Confirmar nueva contraseña -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="confirmar_contrasena" class="form-label">
                                        <i class="fas fa-key"></i> Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" 
                                           placeholder="Confirma la nueva contraseña">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mostrar_contrasenas">
                                        <label class="form-check-label" for="mostrar_contrasenas">
                                            <i class="fas fa-eye"></i> Mostrar contraseñas
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del sistema -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                                        <ul class="mb-0">
                                            <li><strong>Rol:</strong> Acudiente</li>
                                            <li><strong>ID de Usuario:</strong> <?php echo $_SESSION['usuario_id']; ?></li>
                                            <li><strong>Fecha de registro:</strong> <?php echo $acudiente['fecha_creacion'] ?? 'No disponible'; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                    <a href="index.php?accion=dashboard_acudiente" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb"></i> Consejos de Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-shield-alt"></i> Protege tu cuenta:</h6>
                                <ul>
                                    <li>Usa una contraseña única y segura</li>
                                    <li>No compartas tus credenciales</li>
                                    <li>Cambia tu contraseña regularmente</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-user-check"></i> Mantén tu información actualizada:</h6>
                                <ul>
                                    <li>Actualiza tu teléfono si cambia</li>
                                    <li>Mantén un email válido</li>
                                    <li>Verifica que tu ciudad esté correcta</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2024 Sistema de Eventos - Editar Perfil</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar/ocultar contraseñas
        document.getElementById('mostrar_contrasenas').addEventListener('change', function() {
            const contrasenas = [
                document.getElementById('contrasena_actual'),
                document.getElementById('nueva_contrasena'),
                document.getElementById('confirmar_contrasena')
            ];
            
            contrasenas.forEach(input => {
                if (input) {
                    input.type = this.checked ? 'text' : 'password';
                }
            });
        });

        // Validación de contraseñas
        document.querySelector('form').addEventListener('submit', function(e) {
            const nuevaContrasena = document.getElementById('nueva_contrasena').value;
            const confirmarContrasena = document.getElementById('confirmar_contrasena').value;
            
            if (nuevaContrasena || confirmarContrasena) {
                if (nuevaContrasena.length < 6) {
                    alert('La nueva contraseña debe tener al menos 6 caracteres');
                    e.preventDefault();
                    return;
                }
                
                if (nuevaContrasena !== confirmarContrasena) {
                    alert('Las contraseñas no coinciden');
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>
</html> 