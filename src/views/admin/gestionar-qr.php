<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Códigos QR - Sistema de Gestión</title>
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
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .qr-scanner {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-scanner.active {
            border-color: var(--color-primario);
            background: rgba(0, 123, 255, 0.05);
        }
        .section-title {
            color: var(--color-primario);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }
        .qr-badge {
            background: linear-gradient(45deg, var(--color-primario), #0056b3);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }
        .grado-icon {
            position: relative;
            display: inline-block;
        }
        .grado-numero {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            color: var(--color-primario);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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
                <a class="nav-link" href="index.php?action=eventos">
                    <i class="fas fa-calendar me-1"></i>Eventos
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
                    <i class="fas fa-qrcode text-primary me-2"></i>Gestionar Códigos QR
                </h1>
                <p class="text-muted mb-0">Genera y gestiona códigos QR para eventos y personas</p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
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

        <?php if (isset($_SESSION['warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['warning']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['warning']); endif; ?>

        <!-- QR Scanner Section -->
        <div class="qr-card">
            <h4 class="section-title">
                <i class="fas fa-camera me-2"></i>Escáner de Códigos QR
            </h4>
            
            <div class="qr-scanner" id="qrScanner">
                <i class="fas fa-qrcode fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-3">Escáner de QR</h5>
                <p class="text-muted mb-4">Escanea códigos QR para registrar asistencia o ver información</p>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="qrInput" class="form-label">
                                <i class="fas fa-keyboard me-1"></i>Ingresar Código QR Manualmente
                            </label>
                            <textarea class="form-control" id="qrInput" rows="3" 
                                      placeholder="Pega aquí el contenido del código QR..."></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="procesarQR()">
                            <i class="fas fa-search me-2"></i>Procesar QR
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Resultado del QR -->
            <div id="qrResult" class="mt-4" style="display: none;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información del QR
                        </h5>
                    </div>
                    <div class="card-body" id="qrResultContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- QR de Eventos -->
            <div class="col-md-6">
                <div class="qr-card">
                    <h4 class="section-title">
                        <i class="fas fa-calendar-check me-2"></i>Códigos QR de Eventos
                    </h4>
                    
                    <?php if (empty($qr_eventos)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No hay QR generados</h6>
                        <p class="text-muted">Los códigos QR de eventos aparecerán aquí</p>
                    </div>
                    <?php else: ?>
                    
                    <?php foreach ($qr_eventos as $qr_evento): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <img src="<?php echo htmlspecialchars($qr_evento['qr']); ?>" 
                                         alt="QR Evento" class="qr-image">
                                </div>
                                <div class="col-md-8">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($qr_evento['nom_evento']); ?></h6>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($qr_evento['fecha_inicio'])); ?>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo htmlspecialchars($qr_evento['qr']); ?>" 
                                           class="btn btn-sm btn-outline-primary" download>
                                            <i class="fas fa-download me-1"></i>Descargar
                                        </a>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="mostrarQR('<?php echo htmlspecialchars($qr_evento['qr']); ?>')">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </button>
                                        <form method="POST" action="index.php?action=eliminar-qr-evento" class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar el QR de este evento?')">
                                            <input type="hidden" name="cod_evento" value="<?php echo htmlspecialchars($qr_evento['cod_evento']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                </div>
            </div>

            <!-- QR de Personas por Grado -->
            <div class="col-md-6">
                <div class="qr-card">
                    <h4 class="section-title">
                        <i class="fas fa-graduation-cap me-2"></i>Códigos QR por Grado
                    </h4>
                    
                    <?php if (empty($estructura_qr_estudiantes)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No hay QR organizados por grado</h6>
                        <p class="text-muted">Los códigos QR de estudiantes aparecerán organizados por grado</p>
                    </div>
                    <?php else: ?>
                    
                    <?php foreach ($estructura_qr_estudiantes as $grado): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="grado-icon">
                                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                        <div class="grado-numero"><?php echo htmlspecialchars($grado['grado_codigo']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($grado['grado_nombre']); ?></h6>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-folder me-1"></i>
                                        Directorio: <?php echo htmlspecialchars($grado['directorio']); ?>
                                    </p>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted">Estudiantes</small>
                                            <div class="fw-bold text-primary"><?php echo $grado['total_estudiantes']; ?></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">QR Generados</small>
                                            <div class="fw-bold text-success"><?php echo $grado['archivos']; ?></div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="index.php?action=ver-qr-grado&grado=<?php echo urlencode($grado['grado_codigo']); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver QR del Grado
                                        </a>
                                        <a href="index.php?action=descargar-qr-grado&grado=<?php echo urlencode($grado['grado_codigo']); ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-1"></i>Descargar Todo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Generación Masiva de QR -->
        <div class="qr-card">
            <h4 class="section-title">
                <i class="fas fa-rocket me-2"></i>Generación Masiva de QR
            </h4>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                            <h6 class="mb-2">Generar QR por Grado</h6>
                            <p class="text-muted small mb-3">Genera QR para todos los estudiantes de un grado específico</p>
                            
                            <form method="POST" action="index.php?action=generar-qr-masivo-grado" class="mb-2">
                                <select name="cod_grado" class="form-select mb-2" required>
                                    <option value="">Seleccionar Grado</option>
                                    <option value="6">Grado 6 (Todos los grupos)</option>
                                    <option value="7">Grado 7 (Todos los grupos)</option>
                                    <option value="8">Grado 8 (Todos los grupos)</option>
                                    <option value="9">Grado 9 (Todos los grupos)</option>
                                    <option value="10">Grado 10 (Todos los grupos)</option>
                                    <option value="11">Grado 11 (Todos los grupos)</option>
                                </select>
                                <button type="submit" class="btn btn-primary w-100" onclick="return confirm('¿Generar QR para todos los estudiantes de este grado?')">
                                    <i class="fas fa-magic me-2"></i>Generar QR Grado
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h6 class="mb-2">Generar QR Todos los Estudiantes</h6>
                            <p class="text-muted small mb-3">Genera QR para todos los estudiantes sin QR</p>
                            
                            <form method="POST" action="index.php?action=generar-qr-masivo-todos">
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('¿Generar QR para TODOS los estudiantes? Esto puede tomar tiempo.')">
                                    <i class="fas fa-magic me-2"></i>Generar QR Todos
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-warning mb-3"></i>
                            <h6 class="mb-2">Generar QR Todos los Eventos</h6>
                            <p class="text-muted small mb-3">Genera QR para todos los eventos sin QR</p>
                            
                            <form method="POST" action="index.php?action=generar-qr-masivo-eventos">
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('¿Generar QR para todos los eventos?')">
                                    <i class="fas fa-magic me-2"></i>Generar QR Eventos
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eliminación Masiva de QR -->
        <div class="qr-card">
            <h4 class="section-title text-danger">
                <i class="fas fa-trash me-2"></i>Eliminación Masiva de QR
            </h4>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>¡Atención!</strong> Esta acción eliminará permanentemente los archivos QR y no se puede deshacer.
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-graduation-cap fa-3x text-danger mb-3"></i>
                            <h6 class="card-title">Eliminar QR por Grado</h6>
                            <p class="card-text text-muted small">Elimina QR de todos los estudiantes de un grado</p>
                            <form method="POST" action="index.php?action=eliminar-qr-masivo-grado" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar todos los QR del grado seleccionado?')">
                                <select name="cod_grado" class="form-select mb-2" required>
                                    <option value="">Selecciona el grado</option>
                                    <option value="6">Grado 6 (todos los grupos)</option>
                                    <option value="7">Grado 7 (todos los grupos)</option>
                                    <option value="8">Grado 8 (todos los grupos)</option>
                                    <option value="9">Grado 9 (todos los grupos)</option>
                                    <option value="10">Grado 10 (todos los grupos)</option>
                                    <option value="11">Grado 11 (todos los grupos)</option>
                                </select>
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-danger mb-3"></i>
                            <h6 class="card-title">Eliminar QR de Personas</h6>
                            <p class="card-text text-muted small">Elimina todos los QR de estudiantes, profesores, etc.</p>
                            <form method="POST" action="index.php?action=eliminar-qr-masivo-personas" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar todos los QR de personas?')">
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-trash me-1"></i>Eliminar Personas
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-danger mb-3"></i>
                            <h6 class="card-title">Eliminar QR de Eventos</h6>
                            <p class="card-text text-muted small">Elimina todos los QR de eventos</p>
                            <form method="POST" action="index.php?action=eliminar-qr-masivo-eventos" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar todos los QR de eventos?')">
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-trash me-1"></i>Eliminar Eventos
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-bomb fa-3x text-danger mb-3"></i>
                            <h6 class="card-title">Eliminar TODOS los QR</h6>
                            <p class="card-text text-muted small">Elimina todos los QR del sistema</p>
                            <form method="POST" action="index.php?action=eliminar-qr-masivo-todos" 
                                  onsubmit="return confirm('¿Estás SEGURO de eliminar TODOS los QR del sistema? Esta acción no se puede deshacer.')">
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-bomb me-1"></i>Eliminar Todo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="qr-card">
            <h4 class="section-title">
                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
            </h4>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="index.php?action=eventos" class="btn btn-outline-primary w-100">
                        <i class="fas fa-calendar-plus me-2"></i>Generar QR Evento
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="index.php?action=personas" class="btn btn-outline-success w-100">
                        <i class="fas fa-user-plus me-2"></i>Generar QR Persona
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="index.php?action=participantes&controller=registrar" class="btn btn-outline-warning w-100">
                        <i class="fas fa-clipboard-check me-2"></i>Registrar Asistencia
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="#" class="btn btn-outline-info w-100" onclick="abrirScanner()">
                        <i class="fas fa-camera me-2"></i>Abrir Escáner
                    </a>
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
                        <i class="fas fa-qrcode me-2"></i>Código QR
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
        function procesarQR() {
            const qrContent = document.getElementById('qrInput').value.trim();
            
            if (!qrContent) {
                alert('Por favor ingresa el contenido del código QR');
                return;
            }
            
            // Simular procesamiento (en un sistema real, aquí se enviaría al servidor)
            try {
                const data = JSON.parse(qrContent);
                mostrarResultadoQR(data);
            } catch (e) {
                alert('Código QR inválido');
            }
        }
        
        function mostrarResultadoQR(data) {
            const resultDiv = document.getElementById('qrResult');
            const contentDiv = document.getElementById('qrResultContent');
            
            let html = '';
            
            if (data.tipo === 'evento') {
                html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar me-2"></i>Información del Evento</h6>
                            <p><strong>Nombre:</strong> ${data.nombre}</p>
                            <p><strong>Código:</strong> ${data.cod_evento}</p>
                            <p><strong>Fecha:</strong> ${data.fecha}</p>
                            <p><strong>Ubicación:</strong> ${data.ubicacion}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-cog me-2"></i>Acciones</h6>
                            <button class="btn btn-success btn-sm" onclick="registrarAsistencia('${data.cod_evento}')">
                                <i class="fas fa-clipboard-check me-1"></i>Registrar Asistencia
                            </button>
                        </div>
                    </div>
                `;
            } else if (data.tipo === 'persona') {
                html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Información de la Persona</h6>
                            <p><strong>Nombre:</strong> ${data.nombre}</p>
                            <p><strong>ID:</strong> ${data.id_user}</p>
                            <p><strong>Tipo:</strong> ${data.tipo}</p>
                            <p><strong>Email:</strong> ${data.email || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-cog me-2"></i>Acciones</h6>
                            <a href="index.php?action=personas&controller=edit&id=${data.id_user}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Editar Persona
                            </a>
                        </div>
                    </div>
                `;
            }
            
            contentDiv.innerHTML = html;
            resultDiv.style.display = 'block';
        }
        
        function mostrarQR(qrPath) {
            document.getElementById('qrModalImage').src = qrPath;
            document.getElementById('qrDownloadLink').href = qrPath;
            new bootstrap.Modal(document.getElementById('qrModal')).show();
        }
        
        function registrarAsistencia(codEvento) {
            // Aquí se implementaría la lógica para registrar asistencia
            alert('Funcionalidad de registro de asistencia por QR en desarrollo');
        }
        
        function abrirScanner() {
            alert('Funcionalidad de escáner de cámara en desarrollo');
        }
    </script>
</body>
</html> 