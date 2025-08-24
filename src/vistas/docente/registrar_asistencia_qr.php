<?php
// Verificar que el usuario es docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
    header('Location: index.php?accion=login');
    exit;
}

// Incluir controlador de docente
require_once '../src/controllers/DocenteController.php';
$docenteController = new DocenteController($pdo);

// Obtener eventos disponibles
$eventos = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM evento ORDER BY fecha_inicio DESC");
    $stmt->execute();
    $eventos = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error obteniendo eventos: " . $e->getMessage());
}

// Obtener estadísticas de asistencia registrada por el docente
$asistenciasRegistradas = 0;
try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM asistencia_evento 
        WHERE docente_registro = ? AND DATE(fecha_registro) = CURDATE()
    ");
    $stmt->execute([$_SESSION['usuario_id']]);
    $asistenciasRegistradas = $stmt->fetchColumn();
} catch (Exception $e) {
    error_log("Error obteniendo estadísticas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia QR - Panel Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        
        .scanner-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 10;
            pointer-events: none;
        }
        
        .scan-area {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid #fff;
            border-radius: 10px;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
        }
        
        .scan-area::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 2px solid #007bff;
            border-radius: 10px;
            animation: scan 2s linear infinite;
        }
        
        @keyframes scan {
            0% { border-color: #007bff; }
            50% { border-color: #28a745; }
            100% { border-color: #007bff; }
        }
        
        .qr-result {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .manual-input {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white">👨‍🏫 Panel Docente</h4>
                    <p class="text-white-50"><?php echo $_SESSION['usuario_nombre']; ?></p>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php?accion=dashboard_docente">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?accion=ver_estudiantes_docente">
                        <i class="fas fa-users me-2"></i> Ver Estudiantes
                    </a>
                    <a class="nav-link" href="index.php?accion=estudiantes_grupo_docente">
                        <i class="fas fa-user-graduate me-2"></i> Mi Grupo
                    </a>
                    <a class="nav-link active" href="index.php?accion=registrar_asistencia_qr_docente">
                        <i class="fas fa-qrcode me-2"></i> Escanear QR
                    </a>
                    <a class="nav-link" href="index.php?accion=registrar_asistencia_docente">
                        <i class="fas fa-clipboard-check me-2"></i> Registro Manual
                    </a>
                    <a class="nav-link" href="index.php?accion=solicitar_evento_docente">
                        <i class="fas fa-calendar-plus me-2"></i> Solicitar Evento
                    </a>
                    <a class="nav-link" href="index.php?accion=mis_peticiones_docente">
                        <i class="fas fa-list me-2"></i> Mis Peticiones
                    </a>
                    <hr class="text-white-50">
                    <a class="nav-link" href="index.php?accion=logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📱 Escanear Códigos QR</h2>
                <a href="index.php?accion=dashboard_docente" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <!-- Estadísticas del día -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-qrcode fa-2x mb-2"></i>
                            <h4 id="asistenciasHoy"><?php echo $asistenciasRegistradas; ?></h4>
                            <p class="mb-0">Asistencias Hoy</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4 id="totalAsistencias">0</h4>
                            <p class="mb-0">Total Evento</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar fa-2x mb-2"></i>
                            <h4><?php echo count($eventos); ?></h4>
                            <p class="mb-0">Eventos Disponibles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-camera fa-2x mb-2"></i>
                            <h4 id="scannerStatus">Inactivo</h4>
                            <p class="mb-0">Estado Cámara</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información importante -->
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i>Instrucciones de Uso</h5>
                <ul class="mb-0">
                    <li><strong>1.</strong> Selecciona el evento para el cual registrarás asistencia</li>
                    <li><strong>2.</strong> Los estudiantes escanean su código QR personal</li>
                    <li><strong>3.</strong> El sistema registra automáticamente la asistencia</li>
                    <li><strong>4.</strong> Puedes ver los registros en tiempo real</li>
                </ul>
            </div>

            <div class="row">
                <!-- Escáner QR -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-camera me-2"></i>Escáner de Códigos QR
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Selección de evento -->
                            <div class="mb-4">
                                <label for="eventoSelect" class="form-label">
                                    <strong>Selecciona el Evento:</strong>
                                </label>
                                <select class="form-select form-select-lg" id="eventoSelect" required>
                                    <option value="">Selecciona un evento para comenzar...</option>
                                    <?php foreach ($eventos as $evento): ?>
                                    <option value="<?php echo $evento['cod_evento']; ?>">
                                        <?php echo htmlspecialchars($evento['nom_evento']); ?> 
                                        (<?php echo date('d/m/Y H:i', strtotime($evento['fecha_inicio'])); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Controles del escáner -->
                            <div class="text-center mb-3">
                                <button id="startScanner" class="btn btn-success btn-lg me-2">
                                    <i class="fas fa-play me-2"></i>Iniciar Cámara
                                </button>
                                <button id="stopScanner" class="btn btn-danger btn-lg" style="display: none;">
                                    <i class="fas fa-stop me-2"></i>Detener Cámara
                                </button>
                            </div>

                            <!-- Área del escáner -->
                            <div class="scanner-container">
                                <div id="reader"></div>
                                <div class="camera-overlay">
                                    <div class="scan-area"></div>
                                </div>
                            </div>

                            <!-- Resultado del QR -->
                            <div id="qrResult" class="qr-result" style="display: none;">
                                <h6><i class="fas fa-qrcode me-2"></i>Información del QR Escaneado</h6>
                                <div id="qrInfo"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-md-4">
                    <!-- Entrada manual -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-keyboard me-2"></i>Entrada Manual
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="manualForm">
                                <div class="mb-3">
                                    <label for="manualEvento" class="form-label">Evento:</label>
                                    <select class="form-select" id="manualEvento" required>
                                        <option value="">Seleccionar evento</option>
                                        <?php foreach ($eventos as $evento): ?>
                                        <option value="<?php echo $evento['cod_evento']; ?>">
                                            <?php echo htmlspecialchars($evento['nom_evento']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="manualId" class="form-label">ID del Estudiante:</label>
                                    <input type="text" class="form-control" id="manualId" placeholder="Ingresa el ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Registrar Manualmente
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Registros recientes -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>Registros Recientes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="recentRegistrations">
                                <p class="text-muted text-center">No hay registros recientes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificaciones -->
<div id="notificationContainer" class="notification"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let html5QrcodeScanner = null;
let isScanning = false;

// Inicializar escáner
document.getElementById('startScanner').addEventListener('click', function() {
    const evento = document.getElementById('eventoSelect').value;
    if (!evento) {
        showNotification('Por favor selecciona un evento antes de iniciar la cámara', 'warning');
        return;
    }
    startScanner();
});

document.getElementById('stopScanner').addEventListener('click', function() {
    stopScanner();
});

function startScanner() {
    if (isScanning) return;
    
    html5QrcodeScanner = new Html5Qrcode("reader");
    
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanFailure
    ).then(() => {
        isScanning = true;
        document.getElementById('startScanner').style.display = 'none';
        document.getElementById('stopScanner').style.display = 'inline-block';
        document.getElementById('scannerStatus').textContent = 'Activo';
        showNotification('Cámara iniciada correctamente', 'success');
    }).catch(err => {
        console.error('Error iniciando escáner:', err);
        showNotification('Error al iniciar la cámara. Verifica los permisos.', 'error');
    });
}

function stopScanner() {
    if (!isScanning) return;
    
    html5QrcodeScanner.stop().then(() => {
        isScanning = false;
        document.getElementById('startScanner').style.display = 'inline-block';
        document.getElementById('stopScanner').style.display = 'none';
        document.getElementById('scannerStatus').textContent = 'Inactivo';
        showNotification('Cámara detenida', 'info');
    }).catch(err => {
        console.error('Error deteniendo escáner:', err);
    });
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) {
        html5QrcodeScanner.pause();
    }
    
    console.log('QR detectado:', decodedText);
    showNotification('QR detectado, procesando...', 'info');
    
    procesarQR(decodedText);
    
    setTimeout(() => {
        if (isScanning) {
            html5QrcodeScanner.resume();
            showNotification('Escáner listo para el siguiente QR', 'info');
        }
    }, 3000);
}

function onScanFailure(error) {
    // Silenciar errores de escaneo
}

function procesarQR(qrContent) {
    try {
        const data = JSON.parse(qrContent);
        
        if (data.tipo === 'persona') {
            mostrarResultadoEstudiante(data);
            registrarAsistenciaQR(data.id);
        } else {
            showNotification('Este QR es de un evento, no de una persona', 'error');
        }
        
    } catch (e) {
        console.error('Error procesando QR:', e);
        showNotification('Código QR inválido', 'error');
    }
}

function mostrarResultadoEstudiante(data) {
    const resultDiv = document.getElementById('qrResult');
    const infoDiv = document.getElementById('qrInfo');
    
    infoDiv.innerHTML = `
        <div class="row">
            <div class="col-6">
                <strong>Nombre:</strong><br>
                ${data.nombre || 'N/A'}
            </div>
            <div class="col-6">
                <strong>ID:</strong><br>
                ${data.id}
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <strong>Tipo:</strong><br>
                ${data.tipo_persona || 'N/A'}
            </div>
            <div class="col-6">
                <strong>Email:</strong><br>
                ${data.email || 'N/A'}
            </div>
        </div>
    `;
    
    resultDiv.style.display = 'block';
}

function registrarAsistenciaQR(estudianteId) {
    const eventoId = document.getElementById('eventoSelect').value;
    
    if (!eventoId) {
        showNotification('Por favor selecciona un evento', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('cod_evento', eventoId);
    formData.append('id_user', estudianteId);
    formData.append('docente_id', '<?php echo $_SESSION['usuario_id']; ?>');
    
    fetch('index.php?accion=registrar_asistencia_qr_docente', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('✅ ' + result.message, 'success');
            actualizarRegistrosRecientes();
            actualizarEstadisticas(result.estadisticas);
            
            // Mostrar información adicional del registro exitoso
            if (result.tipo === 'nueva') {
                mostrarInformacionRegistro(result);
            }
        } else {
            if (result.tipo === 'duplicado') {
                showNotification('⚠️ ' + result.message, 'warning');
                mostrarInformacionDuplicado(result);
            } else {
                showNotification('❌ ' + result.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al registrar asistencia', 'error');
    });
}

function actualizarEstadisticas(stats) {
    if (stats) {
        document.getElementById('totalAsistencias').textContent = stats.total_asistencias || 0;
        document.getElementById('asistenciasHoy').textContent = stats.asistencias_hoy || 0;
    }
}

function mostrarInformacionRegistro(result) {
    const resultDiv = document.getElementById('qrResult');
    const infoDiv = document.getElementById('qrInfo');
    
    infoDiv.innerHTML = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle me-2"></i>Registro Exitoso</h6>
            <div class="row">
                <div class="col-6">
                    <strong>Estudiante:</strong><br>
                    ${result.estudiante.nom_user}
                </div>
                <div class="col-6">
                    <strong>Grupo:</strong><br>
                    ${result.estudiante.grupo}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <strong>Evento:</strong><br>
                    ${result.evento.nom_evento}
                </div>
                <div class="col-6">
                    <strong>Fecha:</strong><br>
                    ${new Date().toLocaleString()}
                </div>
            </div>
        </div>
    `;
    
    resultDiv.style.display = 'block';
}

function mostrarInformacionDuplicado(result) {
    const resultDiv = document.getElementById('qrResult');
    const infoDiv = document.getElementById('qrInfo');
    
    const fechaRegistro = new Date(result.fecha_registro).toLocaleString();
    
    infoDiv.innerHTML = `
        <div class="alert alert-warning">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Asistencia Ya Registrada</h6>
            <div class="row">
                <div class="col-6">
                    <strong>Estudiante:</strong><br>
                    ${result.estudiante.nom_user}
                </div>
                <div class="col-6">
                    <strong>Grupo:</strong><br>
                    ${result.estudiante.grupo}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <strong>Evento:</strong><br>
                    ${result.evento.nom_evento}
                </div>
                <div class="col-6">
                    <strong>Registrado el:</strong><br>
                    ${fechaRegistro}
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    No se puede registrar asistencia duplicada para el mismo evento
                </small>
            </div>
        </div>
    `;
    
    resultDiv.style.display = 'block';
}

// Formulario manual
document.getElementById('manualForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const eventoId = document.getElementById('manualEvento').value;
    const estudianteId = document.getElementById('manualId').value;
    
    if (!eventoId || !estudianteId) {
        showNotification('Por favor completa todos los campos', 'warning');
        return;
    }
    
    registrarAsistenciaQR(estudianteId);
    document.getElementById('manualForm').reset();
});

function showNotification(message, type) {
    const container = document.getElementById('notificationContainer');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    container.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

function actualizarRegistrosRecientes() {
    // Aquí puedes implementar la actualización de registros recientes
    // Por ahora solo mostraremos un mensaje
    const container = document.getElementById('recentRegistrations');
    container.innerHTML = '<p class="text-success text-center">Registro actualizado</p>';
}

// Cambio de evento
document.getElementById('eventoSelect').addEventListener('change', function() {
    const eventoId = this.value;
    if (eventoId) {
        actualizarEstadisticasEvento(eventoId);
    }
    
    if (isScanning) {
        showNotification('Evento cambiado. El escáner continuará con el nuevo evento.', 'info');
    }
});

function actualizarEstadisticasEvento(eventoId) {
    const formData = new FormData();
    formData.append('cod_evento', eventoId);
    
    fetch('index.php?accion=verificar_asistencia_docente', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        // Aquí podrías actualizar estadísticas específicas del evento
        console.log('Estadísticas del evento actualizadas');
    })
    .catch(error => {
        console.error('Error actualizando estadísticas:', error);
    });
}
</script>
</body>
</html>
