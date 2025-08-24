<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR del Grado <?php echo htmlspecialchars($grado_info['grado_nombre']); ?> - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .qr-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .qr-card:hover {
            transform: translateY(-5px);
        }
        .qr-image {
            max-width: 150px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .section-title {
            color: var(--color-primario);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }
        .grado-header {
            background: linear-gradient(135deg, var(--color-primario), #0056b3);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .estudiante-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .estudiante-card:hover {
            border-color: var(--color-primario);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
        }
        .estudiante-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .qr-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .stats-card {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-qrcode me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="index.php?action=gestionar-qr">
                    <i class="fas fa-qrcode me-1"></i>Gestionar QR
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
        <!-- Header del Grado -->
        <div class="grado-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-graduation-cap me-3"></i>
                        Grado <?php echo htmlspecialchars($grado_info['grado_nombre']); ?>
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-folder me-2"></i>
                        Directorio: <?php echo htmlspecialchars($grado_info['directorio']); ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stats-card">
                        <h4 class="mb-1"><?php echo count($estudiantes_qr); ?></h4>
                        <small>QR Generados</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?action=gestionar-qr" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Gestión QR
                </a>
            </div>
            <div>
                <form method="POST" action="index.php?action=generar-qr-masivo-grado" class="d-inline me-2">
                    <input type="hidden" name="cod_grado" value="<?php echo htmlspecialchars($grado_info['grado_codigo']); ?>">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('¿Generar QR para todos los estudiantes de este grado?')">
                        <i class="fas fa-magic me-2"></i>Generar QR Faltantes
                    </button>
                </form>
                <a href="index.php?action=descargar-qr-grado&grado=<?php echo urlencode($grado_info['grado_codigo']); ?>" 
                   class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Descargar Todos los QR
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>

        <!-- Lista de Estudiantes con QR -->
        <div class="qr-card">
            <h4 class="section-title">
                <i class="fas fa-users me-2"></i>Estudiantes del Grado <?php echo htmlspecialchars($grado_info['grado_nombre']); ?>
            </h4>
            
            <?php if (empty($estudiantes_qr)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-2">No hay estudiantes con QR generados</h5>
                <p class="text-muted">Los códigos QR de estudiantes aparecerán aquí cuando se generen</p>
                <a href="index.php?action=personas" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Ir a Personas
                </a>
            </div>
            <?php else: ?>
            
            <div class="row">
                <?php foreach ($estudiantes_qr as $estudiante): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="estudiante-card">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="<?php echo htmlspecialchars($estudiante['codigo_qr']); ?>" 
                                     alt="QR <?php echo htmlspecialchars($estudiante['nom_user']); ?>" 
                                     class="qr-image">
                            </div>
                            
                            <div class="estudiante-info">
                                <h6 class="mb-1"><?php echo htmlspecialchars($estudiante['nom_user']); ?></h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-id-card me-1"></i>
                                    ID: <?php echo htmlspecialchars($estudiante['id_user']); ?>
                                </p>
                                <?php if (!empty($estudiante['correo_user'])): ?>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-1"></i>
                                    <?php echo htmlspecialchars($estudiante['correo_user']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="qr-actions">
                                <a href="<?php echo htmlspecialchars($estudiante['codigo_qr']); ?>" 
                                   class="btn btn-sm btn-outline-primary" download>
                                    <i class="fas fa-download me-1"></i>Descargar
                                </a>
                                <button class="btn btn-sm btn-outline-success" 
                                        onclick="mostrarQR('<?php echo htmlspecialchars($estudiante['codigo_qr']); ?>')">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </button>
                                <a href="index.php?action=personas&controller=edit&id=<?php echo $estudiante['id_user']; ?>" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </div>

        <!-- Información del Directorio -->
        <div class="qr-card">
            <h4 class="section-title">
                <i class="fas fa-folder-open me-2"></i>Información del Directorio
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6><i class="fas fa-folder me-2"></i>Ubicación del Directorio</h6>
                            <code class="text-primary"><?php echo htmlspecialchars($grado_info['ruta']); ?></code>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6><i class="fas fa-info-circle me-2"></i>Estadísticas</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="fw-bold text-primary"><?php echo count($estudiantes_qr); ?></div>
                                    <small class="text-muted">QR Generados</small>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-success"><?php echo $grado_info['total_estudiantes']; ?></div>
                                    <small class="text-muted">Total Estudiantes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar QR -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode me-2"></i>Código QR del Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrModalImage" src="" alt="QR Code" class="img-fluid" style="max-width: 400px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a id="qrDownloadLink" href="" class="btn btn-primary" download>
                        <i class="fas fa-download me-2"></i>Descargar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mostrarQR(qrPath) {
            document.getElementById('qrModalImage').src = qrPath;
            document.getElementById('qrDownloadLink').href = qrPath;
            new bootstrap.Modal(document.getElementById('qrModal')).show();
        }
    </script>
</body>
</html> 