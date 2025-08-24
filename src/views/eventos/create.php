<?php

// Obtener materias para el select
$materias = $db->fetchAll("SELECT * FROM materias ORDER BY descripcion");

// Obtener tipos de jornada para el select
$jornadas = $db->fetchAll("SELECT * FROM tipo_jornada ORDER BY descripcion");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cod_evento = 'EVT' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO evento (cod_evento, nom_evento, descripcion, duracion, fecha_inicio, fecha_final, 
                hora, aforo_max, ubicacion, tipo_jornada, materia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $cod_evento,
            $_POST['nom_evento'],
            $_POST['descripcion'],
            $_POST['duracion'],
            $_POST['fecha_inicio'],
            $_POST['fecha_final'],
            $_POST['hora'],
            $_POST['aforo_max'],
            $_POST['ubicacion'],
            $_POST['tipo_jornada'],
            $_POST['materia']
        ];
        
        $db->execute($sql, $params);
        
        $_SESSION['success'] = 'Evento creado exitosamente';
        header('Location: index.php?action=eventos');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error al crear el evento: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema de Gestión</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Inicio</a>
                <a class="nav-link" href="index.php?action=eventos">Eventos</a>
                <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a class="nav-link" href="index.php?action=logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus"></i> Crear Nuevo Evento
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom_evento" class="form-label">Nombre del Evento *</label>
                                    <input type="text" class="form-control" id="nom_evento" name="nom_evento" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="materia" class="form-label">Materia *</label>
                                    <select class="form-select" id="materia" name="materia" required>
                                        <option value="">Seleccionar materia</option>
                                        <?php foreach ($materias as $materia): ?>
                                        <option value="<?php echo $materia['cod_categoria']; ?>">
                                            <?php echo htmlspecialchars($materia['descripcion']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="fecha_final" class="form-label">Fecha de Finalización *</label>
                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="hora" class="form-label">Hora *</label>
                                    <input type="time" class="form-control" id="hora" name="hora" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="duracion" class="form-label">Duración *</label>
                                    <input type="text" class="form-control" id="duracion" name="duracion" placeholder="Ej: 2 horas" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="aforo_max" class="form-label">Aforo Máximo *</label>
                                    <input type="number" class="form-control" id="aforo_max" name="aforo_max" min="1" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="tipo_jornada" class="form-label">Tipo de Jornada *</label>
                                    <select class="form-select" id="tipo_jornada" name="tipo_jornada" required>
                                        <option value="">Seleccionar jornada</option>
                                        <?php foreach ($jornadas as $jornada): ?>
                                        <option value="<?php echo $jornada['cod_jornada']; ?>">
                                            <?php echo htmlspecialchars($jornada['descripcion']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="ubicacion" class="form-label">Ubicación *</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto_evento" class="form-label">Foto del Evento</label>
                                <input type="file" class="form-control" id="foto_evento" name="foto_evento" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?action=eventos" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Crear Evento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validación de fechas
    document.getElementById('fecha_inicio').addEventListener('change', function() {
        document.getElementById('fecha_final').min = this.value;
    });
    
    // Validación de aforo
    document.getElementById('aforo_max').addEventListener('input', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });
    </script>
</body>
</html> 