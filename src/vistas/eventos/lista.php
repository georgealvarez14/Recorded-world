<?php
// Obtener todos los eventos
$stmt = $pdo->query("SELECT * FROM evento ORDER BY fecha_inicio DESC");
$eventos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Inicio</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título y botón crear -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>📅 Lista de Eventos</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?accion=crear_evento" class="btn btn-success">
                    ➕ Crear Nuevo Evento
                </a>
            </div>
        </div>

        <!-- Tabla de eventos -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td><?php echo $evento['cod_evento']; ?></td>
                                <td><?php echo htmlspecialchars($evento['nom_evento']); ?></td>
                                <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                                <td><?php echo $evento['fecha_inicio']; ?></td>
                                <td><?php echo $evento['hora']; ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        👁️ Ver
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning">
                                        ✏️ Editar
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                        🗑️ Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=inicio" class="btn btn-secondary">
                ← Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 