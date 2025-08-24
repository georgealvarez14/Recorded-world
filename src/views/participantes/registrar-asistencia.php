<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
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
        
        .attendance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .status-badge {
            font-size: 0.9em;
            padding: 8px 15px;
            border-radius: 20px;
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row bg-primary text-white py-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>Registro de Asistencia
                        </h4>
                        <small>Escanea el código QR para registrar la entrada</small>
                    </div>
                    <div>
                        <a href="index.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                        <a href="index.php?action=participantes&controller=list" class="btn btn-outline-light btn-sm ms-2">
                            <i class="fas fa-list me-1"></i>Ver Asistencias
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                <!-- Escáner de Cámara -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-camera me-2"></i>Escáner de Cámara
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="scanner-container">
                            <div id="reader"></div>
                            <div class="camera-overlay">
                                <div class="scan-area"></div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button id="startScanner" class="btn btn-success btn-lg">
                                <i class="fas fa-play me-2"></i>Iniciar Cámara
                            </button>
                            <button id="stopScanner" class="btn btn-danger btn-lg ms-2" style="display: none;">
                                <i class="fas fa-stop me-2"></i>Detener Cámara
                            </button>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instrucciones:</strong> Posiciona el código QR dentro del área de escaneo. 
                            El sistema detectará automáticamente el código y registrará la asistencia.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Entrada Manual -->
                <div class="manual-input">
                    <h5 class="mb-3">
                        <i class="fas fa-keyboard me-2"></i>Entrada Manual
                    </h5>
                    <form id="manualForm">
                        <div class="mb-3">
                            <label for="eventoSelect" class="form-label">Evento:</label>
                            <select class="form-select" id="eventoSelect" required>
                                <option value="">Selecciona un evento</option>
                                <?php foreach ($eventos as $evento): ?>
                                <option value="<?php echo htmlspecialchars($evento['cod_evento']); ?>">
                                    <?php echo htmlspecialchars($evento['nom_evento']); ?> 
                                    (<?php echo date('d/m/Y', strtotime($evento['fecha_inicio'])); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="personaInput" class="form-label">ID de Persona:</label>
                            <input type="text" class="form-control" id="personaInput" 
                                   placeholder="Ingresa el ID de la persona" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-2"></i>Registrar Asistencia
                        </button>
                    </form>
                </div>
                
                <!-- Resultado del Escaneo -->
                <div id="qrResult" class="qr-result" style="display: none;">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>Información del QR
                    </h6>
                    <div id="qrResultContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
                
                <!-- Estadísticas Rápidas -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas del Día
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 text-primary mb-1"><?php echo $estadisticas['total_registros']; ?></div>
                                <small class="text-muted">Total Registros</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 text-success mb-1"><?php echo $estadisticas['registros_hoy']; ?></div>
                                <small class="text-muted">Hoy</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Registros Recientes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Registros Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($registros_recientes)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay registros recientes</h6>
                            <p class="text-muted">Los registros de asistencia aparecerán aquí</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Persona</th>
                                        <th>Evento</th>
                                        <th>Fecha/Hora</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registros_recientes as $registro): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($registro['nom_user']); ?></strong>
                                            <br><small class="text-muted">ID: <?php echo $registro['id_user']; ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($registro['nom_evento']); ?></td>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($registro['fecha_registro'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Registrado</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;

        // Inicializar escáner
        document.getElementById('startScanner').addEventListener('click', function() {
            startScanner();
        });

        document.getElementById('stopScanner').addEventListener('click', function() {
            stopScanner();
        });

        function startScanner() {
            if (isScanning) return;
            
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, // Usar cámara trasera si está disponible
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
            }).catch(err => {
                console.error('Error iniciando escáner:', err);
                alert('Error al iniciar la cámara. Verifica los permisos.');
            });
        }

        function stopScanner() {
            if (!isScanning) return;
            
            html5QrcodeScanner.stop().then(() => {
                isScanning = false;
                document.getElementById('startScanner').style.display = 'inline-block';
                document.getElementById('stopScanner').style.display = 'none';
            }).catch(err => {
                console.error('Error deteniendo escáner:', err);
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Detener el escáner temporalmente
            if (isScanning) {
                html5QrcodeScanner.pause();
            }
            
            console.log('QR detectado:', decodedText);
            
            // Procesar el QR
            procesarQR(decodedText);
            
            // Reanudar el escáner después de 2 segundos
            setTimeout(() => {
                if (isScanning) {
                    html5QrcodeScanner.resume();
                }
            }, 2000);
        }

        function onScanFailure(error) {
            // Error de escaneo (normal, no mostrar alerta)
            console.log('Escaneo fallido:', error);
        }

        function procesarQR(qrContent) {
            try {
                const data = JSON.parse(qrContent);
                
                // Mostrar información del QR
                mostrarResultadoQR(data);
                
                // Registrar asistencia automáticamente
                registrarAsistencia(data);
                
            } catch (e) {
                console.error('Error procesando QR:', e);
                alert('Código QR inválido');
            }
        }

        function mostrarResultadoQR(data) {
            const resultDiv = document.getElementById('qrResult');
            const contentDiv = document.getElementById('qrResultContent');
            
            let html = '';
            
            if (data.tipo === 'persona') {
                html = `
                    <div class="attendance-card">
                        <h6><i class="fas fa-user me-2"></i>Persona Detectada</h6>
                        <p><strong>Nombre:</strong> ${data.nom}</p>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Tipo:</strong> ${data.tip}</p>
                        <p><strong>Email:</strong> ${data.em}</p>
                        ${data.gr ? `<p><strong>Grado:</strong> ${data.gr}</p>` : ''}
                        <span class="status-badge bg-success">QR Válido</span>
                    </div>
                `;
            } else if (data.tipo === 'evento') {
                html = `
                    <div class="attendance-card">
                        <h6><i class="fas fa-calendar me-2"></i>Evento Detectado</h6>
                        <p><strong>Evento:</strong> ${data.nom}</p>
                        <p><strong>Código:</strong> ${data.cod}</p>
                        <p><strong>Fecha:</strong> ${data.fec}</p>
                        <p><strong>Ubicación:</strong> ${data.ubi}</p>
                        <span class="status-badge bg-success">QR Válido</span>
                    </div>
                `;
            }
            
            contentDiv.innerHTML = html;
            resultDiv.style.display = 'block';
        }

        function registrarAsistencia(data) {
            // Mostrar diálogo para seleccionar evento
            const eventoSelect = document.getElementById('eventoSelect');
            if (eventoSelect.value === '') {
                alert('Por favor selecciona un evento antes de escanear el QR');
                return;
            }
            
            const formData = new FormData();
            formData.append('cod_evento', eventoSelect.value);
            
            if (data.tipo === 'persona') {
                formData.append('id_user', data.id);
            } else {
                alert('Este QR es de un evento, no de una persona');
                return;
            }
            
            // Enviar registro de asistencia
            fetch('index.php?action=registrar-asistencia-qr', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    mostrarNotificacion('Asistencia registrada correctamente', 'success');
                    // Recargar la página para actualizar registros
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    mostrarNotificacion('Error: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al registrar asistencia', 'error');
            });
        }

        // Formulario manual
        document.getElementById('manualForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('cod_evento', document.getElementById('eventoSelect').value);
            formData.append('id_user', document.getElementById('personaInput').value);
            
            fetch('index.php?action=registrar-asistencia-manual', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    mostrarNotificacion('Asistencia registrada correctamente', 'success');
                    document.getElementById('personaInput').value = '';
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    mostrarNotificacion('Error: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al registrar asistencia', 'error');
            });
        });

        function mostrarNotificacion(mensaje, tipo) {
            const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
            const icon = tipo === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="${icon} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Iniciar escáner automáticamente al cargar la página
        window.addEventListener('load', function() {
            setTimeout(() => {
                startScanner();
            }, 1000);
        });
    </script>
</body>
</html> 