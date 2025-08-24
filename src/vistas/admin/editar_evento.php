<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos - ADMIN</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Dashboard</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2>✏️ Editar Evento</h2>
                        <p class="mb-0">Modificar información del evento</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📝 Información del Evento</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=actualizar_evento_admin">
                            <input type="hidden" name="codigo" value="<?php echo $evento['cod_evento']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="codigo_display" class="form-label">Código del Evento</label>
                                    <input type="text" class="form-control" id="codigo_display" 
                                           value="<?php echo $evento['cod_evento']; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre del Evento *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($evento['nom_evento']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" 
                                           value="<?php echo $evento['fecha_inicio']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="hora" class="form-label">Hora</label>
                                    <input type="time" class="form-control" id="hora" name="hora" 
                                           value="<?php echo $evento['hora']; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                                          placeholder="Describe el evento, objetivos, participantes, etc."><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="index.php?accion=crud_eventos" class="btn btn-secondary me-md-2">
                                            ❌ Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            💾 Actualizar Evento
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ℹ️ Información del Evento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Código:</strong> <?php echo $evento['cod_evento']; ?></p>
                                <p><strong>Fecha de creación:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['fecha_inicio'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Estado:</strong> 
                                    <?php 
                                    $fecha_evento = new DateTime($evento['fecha_inicio']);
                                    $hoy = new DateTime();
                                    if ($fecha_evento < $hoy) {
                                        echo '<span class="badge bg-secondary">Pasado</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Próximo</span>';
                                    }
                                    ?>
                                </p>
                                <p><strong>Días restantes:</strong> 
                                    <?php 
                                    $diferencia = $hoy->diff($fecha_evento);
                                    if ($fecha_evento < $hoy) {
                                        echo 'Evento ya pasó';
                                    } else {
                                        echo $diferencia->days . ' días';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=crud_eventos" class="btn btn-secondary">
                ← Volver al CRUD de Eventos
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 