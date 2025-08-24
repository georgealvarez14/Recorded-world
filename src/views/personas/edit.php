<?php
try {
    $conexion = new PDO('mysql:host=localhost;dbname=registro;', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $nom_user = $_POST['nom_user'];
    $ciudad = $_POST['ciudad'];
    $telef_user = $_POST['telef_user'];
    $correo_user = $_POST['correo_user'];
    $tipo_persona = $_POST['tipo_persona'];
    $cod_grado = $_POST['cod_grado'];
    $grupo = $_POST['grupo'] ?? '';
    $nom_acudiente = $_POST['nom_acudiente'] ?? '';
    $telef_acudiente = $_POST['telef_acudiente'] ?? '';
    $correo_acudiente = $_POST['correo_acudiente'] ?? '';

    // Manejo de la foto
    if (isset($_FILES['foto_persona']) && $_FILES['foto_persona']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['foto_persona']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $file_name;

        $check = getimagesize($_FILES['foto_persona']['tmp_name']);
        if ($check === false) {
            die("El archivo no es una imagen válida.");
        }

        if ($_FILES['foto_persona']['size'] > 5000000) {
            die("La imagen excede el tamaño máximo permitido (5MB).");
        }

        $stmt = $conexion->prepare("SELECT foto_persona FROM persona WHERE id_user = ?");
        $stmt->execute([$id_user]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $foto_antigua = $resultado['foto_persona'];

        if (move_uploaded_file($_FILES['foto_persona']['tmp_name'], $target_file)) {
            $foto_persona = $target_file;
            if (!empty($foto_antigua) && file_exists($foto_antigua)) {
                unlink($foto_antigua);
            }
        } else {
            die("Error al subir la imagen: " . error_get_last()['message']);
        }
    } else {
        $stmt = $conexion->prepare("SELECT foto_persona FROM persona WHERE id_user = ?");
        $stmt->execute([$id_user]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $foto_persona = $result['foto_persona'];
    }

    try {
        $sql = "UPDATE persona SET
                nom_user = :nom_user,
                ciudad = :ciudad,
                telef_user = :telef_user,
                correo_user = :correo_user,
                tipo_persona = :tipo_persona,
                cod_grado = :cod_grado,
                grupo = :grupo,
                nom_acudiente = :nom_acudiente,
                telef_acudiente = :telef_acudiente,
                correo_acudiente = :correo_acudiente,
                foto_persona = :foto_persona
                WHERE id_user = :id_user";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nom_user' => $nom_user,
            ':ciudad' => $ciudad,
            ':telef_user' => $telef_user,
            ':correo_user' => $correo_user,
            ':tipo_persona' => $tipo_persona,
            ':cod_grado' => $cod_grado,
            ':grupo' => $grupo,
            ':nom_acudiente' => $nom_acudiente,
            ':telef_acudiente' => $telef_acudiente,
            ':correo_acudiente' => $correo_acudiente,
            ':foto_persona' => $foto_persona,
            ':id_user' => $id_user
        ]);

        header("Location: Editar_persona.php?id=" . $id_user . "&actualizado=1");
        exit();

    } catch (PDOException $e) {
        die("Error al actualizar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Persona - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(102, 126, 234, 0.05);
        }
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-users me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="index.php?action=personas">
                    <i class="fas fa-users me-1"></i>Personas
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
                    <i class="fas fa-user-edit text-primary me-2"></i>Editar Persona
                </h1>
                <p class="text-muted mb-0">Modifica la información de la persona seleccionada</p>
            </div>
            <a href="index.php?action=personas" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver a Personas
            </a>
        </div>

        <?php
        // Verificar si se pasaron los datos desde el controlador
        if (!isset($persona) || !$persona) {
            echo '<div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>Error: No se pudo cargar la información de la persona
                  </div>';
        }
        ?>

        <?php if ($persona): ?>
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="id_user" value="<?php echo $persona['id_user']; ?>">
            
            <!-- Información de la Foto -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-camera me-2"></i>Foto de Perfil
                </h4>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <?php if (!empty($persona['foto_persona'])): ?>
                        <img src="<?php echo htmlspecialchars($persona['foto_persona']); ?>" 
                             alt="Foto actual" class="profile-image mb-3">
                        <?php else: ?>
                        <div class="profile-image mb-3 d-inline-flex align-items-center justify-content-center bg-secondary text-white">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <div class="upload-area" onclick="document.getElementById('foto_persona').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-1">Haz clic para cambiar la foto</p>
                            <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 5MB)</small>
                        </div>
                        <input type="file" id="foto_persona" name="foto_persona" 
                               accept="image/*" class="d-none" onchange="previewImage(this)">
                    </div>
                </div>
            </div>

            <!-- Información Personal -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-user me-2"></i>Información Personal
                </h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom_user" class="form-label">
                            <i class="fas fa-user me-1"></i>Nombre Completo *
                        </label>
                        <input type="text" class="form-control" id="nom_user" name="nom_user" 
                               value="<?php echo htmlspecialchars($persona['nom_user']); ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingresa el nombre completo.
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="correo_user" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="correo_user" name="correo_user" 
                               value="<?php echo htmlspecialchars($persona['correo_user']); ?>">
                        <div class="invalid-feedback">
                            Por favor ingresa un correo válido.
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="telef_user" class="form-label">
                            <i class="fas fa-phone me-1"></i>Teléfono
                        </label>
                        <input type="tel" class="form-control" id="telef_user" name="telef_user" 
                               value="<?php echo htmlspecialchars($persona['telef_user']); ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>Ciudad *
                        </label>
                        <select class="form-select" id="ciudad" name="ciudad" required>
                            <option value="">Selecciona una ciudad</option>
                            <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?php echo $ciudad['cod_ciudad']; ?>" 
                                    <?php echo ($ciudad['cod_ciudad'] == $persona['ciudad']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ciudad['descripcion']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona una ciudad.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Académica -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-graduation-cap me-2"></i>Información Académica
                </h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tipo_persona" class="form-label">
                            <i class="fas fa-user-tag me-1"></i>Tipo de Persona *
                        </label>
                        <select class="form-select" id="tipo_persona" name="tipo_persona" required>
                            <option value="">Selecciona el tipo</option>
                            <?php foreach ($tipos_persona as $tipo): ?>
                            <option value="<?php echo $tipo['cod_tipo']; ?>" 
                                    <?php echo ($tipo['cod_tipo'] == $persona['tipo_persona']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipo['descripcion']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona el tipo de persona.
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="cod_grado" class="form-label">
                            <i class="fas fa-school me-1"></i>Grado
                        </label>
                        <select class="form-select" id="cod_grado" name="cod_grado">
                            <option value="">Selecciona el grado</option>
                            <?php foreach ($grados as $grado): ?>
                            <option value="<?php echo $grado['cod_grado']; ?>" 
                                    <?php echo ($grado['cod_grado'] == $persona['cod_grado']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grado['descripcion']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="grupo" class="form-label">
                            <i class="fas fa-users me-1"></i>Grupo
                        </label>
                        <input type="text" class="form-control" id="grupo" name="grupo" 
                               value="<?php echo htmlspecialchars($persona['grupo'] ?? ''); ?>" 
                               placeholder="Ej: A, B, C...">
                    </div>
                </div>
            </div>

            <!-- Información del Acudiente -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-user-friends me-2"></i>Información del Acudiente
                </h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nom_acudiente" class="form-label">
                            <i class="fas fa-user me-1"></i>Nombre del Acudiente
                        </label>
                        <input type="text" class="form-control" id="nom_acudiente" name="nom_acudiente" 
                               value="<?php echo htmlspecialchars($persona['nom_acudiente'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="telef_acudiente" class="form-label">
                            <i class="fas fa-phone me-1"></i>Teléfono del Acudiente
                        </label>
                        <input type="tel" class="form-control" id="telef_acudiente" name="telef_acudiente" 
                               value="<?php echo htmlspecialchars($persona['telef_acudiente'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="correo_acudiente" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Correo del Acudiente
                        </label>
                        <input type="email" class="form-control" id="correo_acudiente" name="correo_acudiente" 
                               value="<?php echo htmlspecialchars($persona['correo_acudiente'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Los campos marcados con * son obligatorios
                        </small>
                    </div>
                    <div>
                        <a href="index.php?action=personas" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                        
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
                        <form method="POST" action="index.php?action=generar-qr-persona" class="d-inline ms-2">
                            <input type="hidden" name="id_user" value="<?php echo $persona['id_user']; ?>">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-qrcode me-1"></i>Generar QR
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
        <?php endif; ?>
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

        // Preview de imagen
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.querySelector('.profile-image');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        // Crear nueva imagen si no existe
                        var container = document.querySelector('.col-md-3.text-center');
                        var newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = 'profile-image mb-3';
                        newImg.alt = 'Preview';
                        container.innerHTML = '';
                        container.appendChild(newImg);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Mostrar nombre del archivo seleccionado
        document.getElementById('foto_persona').addEventListener('change', function() {
            var fileName = this.files[0]?.name;
            if (fileName) {
                var uploadArea = document.querySelector('.upload-area');
                uploadArea.innerHTML = `
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="mb-1">Archivo seleccionado: ${fileName}</p>
                    <small class="text-muted">Haz clic para cambiar</small>
                `;
            }
        });
    </script>
</body>
</html>
