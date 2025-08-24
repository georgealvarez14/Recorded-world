<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Personas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-crown me-2"></i>Sistema de Eventos - ADMIN
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="section-title">
            <h2><i class="fas fa-users me-3"></i>Gestión de Personas</h2>
            <p>Administra todas las personas del sistema</p>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Barra de herramientas -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Buscar Personas</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="index.php?accion=crear_persona_admin" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Nueva Persona
                        </a>
                    </div>
                </div>
                
                <form method="GET" action="index.php" class="mt-3">
                    <input type="hidden" name="accion" value="crud_personas">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="buscar" 
                                   value="<?php echo $_GET['buscar'] ?? ''; ?>" 
                                   placeholder="Buscar por nombre o email...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="tipo">
                                <option value="">Todos los tipos</option>
                                <option value="ADM" <?php echo ($_GET['tipo'] ?? '') === 'ADM' ? 'selected' : ''; ?>>Administrador</option>
                                <option value="DOC" <?php echo ($_GET['tipo'] ?? '') === 'DOC' ? 'selected' : ''; ?>>Docente</option>
                                <option value="EST" <?php echo ($_GET['tipo'] ?? '') === 'EST' ? 'selected' : ''; ?>>Estudiante</option>
                                <option value="ACU" <?php echo ($_GET['tipo'] ?? '') === 'ACU' ? 'selected' : ''; ?>>Acudiente</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="grado">
                                <option value="">Todos los grados</option>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM grado ORDER BY descripcion");
                                $grados = $stmt->fetchAll();
                                foreach ($grados as $grado) {
                                    $selected = ($_GET['grado'] ?? '') === $grado['cod_grado'] ? 'selected' : '';
                                    echo "<option value='{$grado['cod_grado']}' {$selected}>{$grado['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de personas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Personas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Grado</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Construir consulta con filtros
                            $where_conditions = ["1=1"];
                            $params = [];
                            
                            if (!empty($_GET['buscar'])) {
                                $buscar = $_GET['buscar'];
                                $where_conditions[] = "(p.nom_user LIKE ? OR p.correo_user LIKE ?)";
                                $params[] = "%$buscar%";
                                $params[] = "%$buscar%";
                            }
                            
                            if (!empty($_GET['tipo'])) {
                                $where_conditions[] = "p.tipo_persona = ?";
                                $params[] = $_GET['tipo'];
                            }
                            
                            if (!empty($_GET['grado'])) {
                                $where_conditions[] = "p.cod_grado = ?";
                                $params[] = $_GET['grado'];
                            }
                            
                            $sql = "
                                SELECT p.*, g.descripcion as nombre_grado 
                                FROM persona p 
                                LEFT JOIN grado g ON p.cod_grado = g.cod_grado 
                                WHERE " . implode(" AND ", $where_conditions) . "
                                ORDER BY p.nom_user
                                LIMIT 50
                            ";
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute($params);
                            $personas = $stmt->fetchAll();
                            
                            if (empty($personas)) {
                                echo "<tr><td colspan='7' class='text-center text-muted'>No se encontraron personas</td></tr>";
                            } else {
                                foreach ($personas as $persona) {
                                    // Determinar color del badge según tipo
                                    $tipo_colors = [
                                        'ADM' => 'danger',
                                        'DOC' => 'info',
                                        'EST' => 'warning',
                                        'ACU' => 'secondary'
                                    ];
                                    $tipo_color = $tipo_colors[$persona['tipo_persona']] ?? 'secondary';
                                    
                                    $tipo_labels = [
                                        'ADM' => 'Administrador',
                                        'DOC' => 'Docente',
                                        'EST' => 'Estudiante',
                                        'ACU' => 'Acudiente'
                                    ];
                                    $tipo_label = $tipo_labels[$persona['tipo_persona']] ?? $persona['tipo_persona'];
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $persona['id_user']; ?></strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <?php if (isset($persona['foto_persona']) && $persona['foto_persona']): ?>
                                                        <img src="<?php echo $persona['foto_persona']; ?>" 
                                                             alt="Foto" class="rounded-circle" width="32" height="32">
                                                    <?php else: ?>
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($persona['nom_user']); ?></strong>
                                                    <br><small class="text-muted"><?php echo $persona['correo_user']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($persona['correo_user']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $tipo_color; ?>">
                                                <?php echo $tipo_label; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $persona['nombre_grado'] ?? 'No asignado'; ?></td>
                                        <td><?php echo $persona['telef_user'] ?? 'No registrado'; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?accion=ver_persona_admin&id=<?php echo $persona['id_user']; ?>" 
                                                   class="btn btn-sm btn-info" title="Ver información completa">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="index.php?accion=editar_persona_admin&id=<?php echo $persona['id_user']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if (isset($persona['codigo_qr']) && $persona['codigo_qr'] && file_exists($persona['codigo_qr'])): ?>
                                                    <a href="<?php echo $persona['codigo_qr']; ?>" 
                                                       class="btn btn-success btn-sm" title="Ver QR" target="_blank">
                                                        <i class="fas fa-qrcode"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?accion=generar_qr_persona&id=<?php echo $persona['id_user']; ?>" 
                                                       class="btn btn-primary btn-sm" title="Generar QR">
                                                        <i class="fas fa-qrcode"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmarEliminar(<?php echo $persona['id_user']; ?>, '<?php echo htmlspecialchars($persona['nom_user']); ?>')"
                                                        title="Eliminar persona">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="stat-item primary">
                    <h3><?php echo count($personas); ?></h3>
                    <p><i class="fas fa-users me-2"></i>Personas Mostradas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item success">
                    <h3><?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'EST'");
                        echo $stmt->fetch()['total'];
                    ?></h3>
                    <p><i class="fas fa-user-graduate me-2"></i>Estudiantes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item info">
                    <h3><?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'DOC'");
                        echo $stmt->fetch()['total'];
                    ?></h3>
                    <p><i class="fas fa-chalkboard-teacher me-2"></i>Docentes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item warning">
                    <h3><?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'ACU'");
                        echo $stmt->fetch()['total'];
                    ?></h3>
                    <p><i class="fas fa-user-friends me-2"></i>Acudientes</p>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="text-center mt-4">
            <a href="index.php?accion=inicio" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        function confirmarEliminar(id, nombre) {
            if (confirm(`¿Estás seguro de eliminar a "${nombre}"? Esta acción no se puede deshacer.`)) {
                window.location.href = `index.php?accion=eliminar_persona_admin&id=${id}`;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 