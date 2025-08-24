<?php
// Obtener todas las personas
$stmt = $pdo->query("SELECT * FROM persona ORDER BY nom_user");
$personas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Personas</title>
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
                <h2>👥 Lista de Personas</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?accion=crear_persona" class="btn btn-success">
                    ➕ Crear Nueva Persona
                </a>
            </div>
        </div>

        <!-- Tabla de personas -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personas as $persona): ?>
                            <tr>
                                <td><?php echo $persona['id_user']; ?></td>
                                <td><?php echo htmlspecialchars($persona['nom_user']); ?></td>
                                <td><?php echo htmlspecialchars($persona['correo_user']); ?></td>
                                <td>
                                    <?php 
                                    $tipos = [
                                        'ADM' => '<span class="badge bg-danger">Admin</span>',
                                        'DOC' => '<span class="badge bg-primary">Docente</span>',
                                        'EST' => '<span class="badge bg-success">Estudiante</span>',
                                        'ACU' => '<span class="badge bg-warning">Acudiente</span>'
                                    ];
                                    echo $tipos[$persona['tipo_persona']] ?? $persona['tipo_persona'];
                                    ?>
                                </td>
                                <td>
                                    <a href="index.php?accion=editar_persona&id=<?php echo $persona['id_user']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        ✏️ Editar
                                    </a>
                                    <a href="index.php?accion=eliminar_persona&id=<?php echo $persona['id_user']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar esta persona?')">
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