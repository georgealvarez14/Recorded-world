<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Códigos QR - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: rgba(0,0,0,0.1);">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-crown me-2"></i>Sistema de Eventos - ADMIN
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=crud_personas">
                    <i class="fas fa-users me-2"></i>Gestionar Personas
                </a>
                <a class="nav-link" href="index.php?accion=inicio">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Hero Section -->
        <div class="text-center mb-5">
            <h1 class="text-white mb-3">
                <i class="fas fa-qrcode me-3"></i>
                Sistema de Códigos QR
            </h1>
            <p class="text-white-50 lead">Gestión completa de códigos QR para personas y eventos</p>
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

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="bg-primary text-white rounded p-3 text-center">
                    <div class="h2 fw-bold">
                        <?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'EST'");
                        echo $stmt->fetch()['total'];
                        ?>
                    </div>
                    <p class="mb-0"><i class="fas fa-user-graduate me-2"></i>Estudiantes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-success text-white rounded p-3 text-center">
                    <div class="h2 fw-bold">
                        <?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona WHERE codigo_qr IS NOT NULL");
                        echo $stmt->fetch()['total'];
                        ?>
                    </div>
                    <p class="mb-0"><i class="fas fa-qrcode me-2"></i>QR Personas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-info text-white rounded p-3 text-center">
                    <div class="h2 fw-bold">
                        <?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento");
                        echo $stmt->fetch()['total'];
                        ?>
                    </div>
                    <p class="mb-0"><i class="fas fa-calendar me-2"></i>Eventos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-warning text-white rounded p-3 text-center">
                    <div class="h2 fw-bold">
                        <?php 
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento WHERE qr IS NOT NULL");
                        echo $stmt->fetch()['total'];
                        ?>
                    </div>
                    <p class="mb-0"><i class="fas fa-qrcode me-2"></i>QR Eventos</p>
                </div>
            </div>
        </div>

        <!-- Tabs de Navegación -->
        <ul class="nav nav-tabs mb-4" id="qrTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="generar-tab" data-bs-toggle="tab" data-bs-target="#generar" type="button" role="tab">
                    <i class="fas fa-plus-circle me-2"></i>Generar QR
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="eliminar-tab" data-bs-toggle="tab" data-bs-target="#eliminar" type="button" role="tab">
                    <i class="fas fa-trash me-2"></i>Eliminar QR
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="gestionar-tab" data-bs-toggle="tab" data-bs-target="#gestionar" type="button" role="tab">
                    <i class="fas fa-cog me-2"></i>Gestionar QR
                </button>
            </li>
        </ul>

        <!-- Contenido de los Tabs -->
        <div class="tab-content" id="qrTabsContent">
            <!-- Tab Generar QR -->
            <div class="tab-pane fade show active" id="generar" role="tabpanel">
                <div class="row">
                    <!-- Generación Masiva por Grado -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Generación Masiva por Grado
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Genera QR para todos los estudiantes de un grado específico.</p>
                                
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion" value="generar_qr_masivo_grado">
                                    <div class="mb-3">
                                        <label for="grado" class="form-label">Seleccionar Grado:</label>
                                        <select class="form-select" name="grado" id="grado" required>
                                            <option value="">Selecciona un grado...</option>
                                            <?php
                                            $stmt = $pdo->query("SELECT * FROM grado ORDER BY descripcion");
                                            $grados = $stmt->fetchAll();
                                            foreach ($grados as $grado) {
                                                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'EST' AND cod_grado LIKE ?");
                                                $stmt->execute([$grado['cod_grado'] . '%']);
                                                $total_estudiantes = $stmt->fetch()['total'];
                                                
                                                echo "<option value='{$grado['cod_grado']}'>{$grado['descripcion']} ({$total_estudiantes} estudiantes)</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-qrcode me-2"></i>
                                        Generar QR para el Grado
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Generación Masiva por Tipo -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Generación Masiva por Tipo
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Genera QR para todas las personas de un tipo específico.</p>
                                
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion" value="generar_qr_masivo_tipo">
                                    <div class="mb-3">
                                        <label for="tipo_persona" class="form-label">Seleccionar Tipo:</label>
                                        <select class="form-select" name="tipo_persona" id="tipo_persona" required>
                                            <option value="">Selecciona un tipo...</option>
                                            <option value="EST">Estudiantes</option>
                                            <option value="DOC">Docentes</option>
                                            <option value="ACU">Acudientes</option>
                                            <option value="ADM">Administradores</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-qrcode me-2"></i>
                                        Generar QR para el Tipo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Generación para Todos -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-globe me-2"></i>
                                    Generación para Todos
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Genera QR para todas las personas del sistema.</p>
                                
                                <form method="POST" action="index.php" onsubmit="return confirm('¿Estás seguro de generar QR para TODAS las personas? Esto puede tomar tiempo.')">
                                    <input type="hidden" name="accion" value="generar_qr_masivo_todos">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-qrcode me-2"></i>
                                        Generar QR para Todos
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Generación de Eventos -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>
                                    Generación de Eventos
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Genera QR para todos los eventos del sistema.</p>
                                
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="accion" value="generar_qr_masivo_eventos">
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-qrcode me-2"></i>
                                        Generar QR para Eventos
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Eliminar QR -->
            <div class="tab-pane fade" id="eliminar" role="tabpanel">
                <div class="row">
                    <!-- Eliminación por Grado -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-trash me-2"></i>
                                    Eliminar QR por Grado
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Elimina QR de todos los estudiantes de un grado específico.</p>
                                
                                <form method="POST" action="index.php" onsubmit="return confirm('¿Estás seguro de eliminar todos los QR del grado seleccionado?')">
                                    <input type="hidden" name="accion" value="eliminar_qr_masivo_grado">
                                    <div class="mb-3">
                                        <label for="grado_eliminar" class="form-label">Seleccionar Grado:</label>
                                        <select class="form-select" name="grado" id="grado_eliminar" required>
                                            <option value="">Selecciona un grado...</option>
                                            <?php
                                            foreach ($grados as $grado) {
                                                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM persona WHERE tipo_persona = 'EST' AND cod_grado LIKE ? AND codigo_qr IS NOT NULL");
                                                $stmt->execute([$grado['cod_grado'] . '%']);
                                                $total_qr = $stmt->fetch()['total'];
                                                
                                                echo "<option value='{$grado['cod_grado']}'>{$grado['descripcion']} ({$total_qr} QR)</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>
                                        Eliminar QR del Grado
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Eliminación por Tipo -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-trash me-2"></i>
                                    Eliminar QR por Tipo
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Elimina QR de todas las personas de un tipo específico.</p>
                                
                                <form method="POST" action="index.php" onsubmit="return confirm('¿Estás seguro de eliminar todos los QR del tipo seleccionado?')">
                                    <input type="hidden" name="accion" value="eliminar_qr_masivo_tipo">
                                    <div class="mb-3">
                                        <label for="tipo_eliminar" class="form-label">Seleccionar Tipo:</label>
                                        <select class="form-select" name="tipo_persona" id="tipo_eliminar" required>
                                            <option value="">Selecciona un tipo...</option>
                                            <option value="EST">Estudiantes</option>
                                            <option value="DOC">Docentes</option>
                                            <option value="ACU">Acudientes</option>
                                            <option value="ADM">Administradores</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>
                                        Eliminar QR del Tipo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Eliminación de Eventos -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-times me-2"></i>
                                    Eliminar QR de Eventos
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Elimina QR de todos los eventos del sistema.</p>
                                
                                <form method="POST" action="index.php" onsubmit="return confirm('¿Estás seguro de eliminar todos los QR de eventos?')">
                                    <input type="hidden" name="accion" value="eliminar_qr_masivo_eventos">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>
                                        Eliminar QR de Eventos
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Eliminación Total -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Eliminación Total
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Elimina TODOS los QR del sistema (personas y eventos).</p>
                                
                                <form method="POST" action="index.php" onsubmit="return confirm('⚠️ ADVERTENCIA: ¿Estás completamente seguro de eliminar TODOS los QR del sistema? Esta acción no se puede deshacer.')">
                                    <input type="hidden" name="accion" value="eliminar_qr_masivo_todos">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Eliminar TODOS los QR
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Gestionar QR -->
            <div class="tab-pane fade" id="gestionar" role="tabpanel">
                <div class="row">
                    <!-- Accesos Rápidos -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Gestionar Personas
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Accede al CRUD de personas para generar QR individuales.</p>
                                <a href="index.php?accion=crud_personas" class="btn btn-primary">
                                    <i class="fas fa-users me-2"></i>Gestionar Personas
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>
                                    Gestionar Eventos
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Accede al CRUD de eventos para generar QR de eventos.</p>
                                <a href="index.php?accion=crud_eventos" class="btn btn-success">
                                    <i class="fas fa-calendar me-2"></i>Gestionar Eventos
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-eye me-2"></i>
                                    Ver QR Generados
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Visualiza y descarga los QR generados.</p>
                                <a href="index.php?accion=ver_qr" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>Ver QR Generados
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Sistema -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Información del Sistema QR
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-user me-2"></i>QR de Personas:</h6>
                                <ul class="text-muted">
                                    <li>Contiene información de identificación</li>
                                    <li>Se usa para control de asistencia</li>
                                    <li>Incluye ID, nombre, tipo y grado</li>
                                    <li>Se genera automáticamente</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-calendar me-2"></i>QR de Eventos:</h6>
                                <ul class="text-muted">
                                    <li>Contiene información del evento</li>
                                    <li>Se usa para registro de asistencia</li>
                                    <li>Incluye código, nombre, fecha y ubicación</li>
                                    <li>Se puede escanear en el evento</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de navegación -->
        <div class="text-center mt-4">
            <a href="index.php?accion=inicio" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
            <a href="index.php?accion=crud_personas" class="btn btn-primary">
                <i class="fas fa-users me-2"></i>Gestionar Personas
            </a>
            <a href="index.php?accion=crud_eventos" class="btn btn-success">
                <i class="fas fa-calendar me-2"></i>Gestionar Eventos
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 