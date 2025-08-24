<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver QR - Admin</title>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>
                            Código QR de <?php echo htmlspecialchars($persona['nom_user']); ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <!-- Información de la persona -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="p-3 border-bottom">
                                    <strong><i class="fas fa-user me-2"></i>Nombre:</strong>
                                    <span class="ms-2"><?php echo htmlspecialchars($persona['nom_user']); ?></span>
                                </div>
                                <div class="p-3 border-bottom">
                                    <strong><i class="fas fa-id-card me-2"></i>ID:</strong>
                                    <span class="ms-2"><?php echo $persona['id_user']; ?></span>
                                </div>
                                <div class="p-3">
                                    <strong><i class="fas fa-user-tag me-2"></i>Tipo:</strong>
                                    <span class="ms-2"><?php echo $persona['tipo_persona']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border-bottom">
                                    <strong><i class="fas fa-file-code me-2"></i>Archivo QR:</strong>
                                    <span class="ms-2"><?php echo basename($persona['codigo_qr']); ?></span>
                                </div>
                                <div class="p-3 border-bottom">
                                    <strong><i class="fas fa-calendar me-2"></i>Fecha Generación:</strong>
                                    <span class="ms-2"><?php echo date('d/m/Y H:i:s', filemtime($persona['codigo_qr'])); ?></span>
                                </div>
                                <div class="p-3">
                                    <strong><i class="fas fa-hdd me-2"></i>Tamaño:</strong>
                                    <span class="ms-2"><?php echo number_format(filesize($persona['codigo_qr']) / 1024, 2); ?> KB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Imagen del QR -->
                        <div class="text-center p-4 bg-light rounded">
                            <h5 class="mb-3">
                                <i class="fas fa-qrcode me-2"></i>
                                Código QR Generado
                            </h5>
                            <img src="<?php echo $persona['codigo_qr']; ?>" 
                                 alt="QR de <?php echo htmlspecialchars($persona['nom_user']); ?>" 
                                 class="img-fluid border rounded" style="max-width: 300px;">
                            <p class="text-muted mt-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Este código QR contiene la información de identificación de la persona
                            </p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="text-center mt-4">
                            <a href="<?php echo $persona['codigo_qr']; ?>" 
                               class="btn btn-success me-2" 
                               target="_blank"
                               download="<?php echo basename($persona['codigo_qr']); ?>">
                                <i class="fas fa-download me-2"></i>Descargar QR
                            </a>
                            
                            <a href="<?php echo $persona['codigo_qr']; ?>" 
                               class="btn btn-primary me-2" 
                               target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Abrir en Nueva Pestaña
                            </a>
                            
                            <a href="index.php?accion=eliminar_qr_persona&id=<?php echo $persona['id_user']; ?>" 
                               class="btn btn-danger me-2"
                               onclick="return confirm('¿Estás seguro de eliminar este QR?')">
                                <i class="fas fa-trash me-2"></i>Eliminar QR
                            </a>
                            
                            <a href="index.php?accion=crud_personas" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Personas
                            </a>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info mt-4">
                            <h6><i class="fas fa-lightbulb me-2"></i>Información del QR:</h6>
                            <ul class="mb-0">
                                <li>El código QR contiene datos JSON con la información de la persona</li>
                                <li>Se puede usar para control de asistencia y identificación</li>
                                <li>El archivo se guarda en: <code><?php echo $persona['codigo_qr']; ?></code></li>
                                <li>Puedes descargarlo o imprimirlo para uso físico</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 