<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Entrada - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-header {
            background: #343a40;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .main-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 500;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: #495057;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px 20px;
            font-weight: 500;
        }

        .scanner-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }

        .scan-area {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid #fff;
            border-radius: 8px;
        }

        .btn-primary {
            background: #007bff;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-success {
            background: #28a745;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .student-info {
            background: #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #495057;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .entrance-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .entrance-item {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            background: white;
        }

        .entrance-item:last-child {
            border-bottom: none;
        }

        .entrance-item:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-on-time {
            background: #d4edda;
            color: #155724;
        }

        .status-late {
            background: #fff3cd;
            color: #856404;
        }

        .manual-input {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .notification.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .notification.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .notification.info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .simple-nav {
            background: #6c757d;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .simple-nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .simple-nav a:hover {
            background: #5a6268;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header Simple -->
    <div class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-door-open me-2"></i>Control de Entrada</h1>
                <div>
                    <a href="index.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                    <a href="index.php?action=admin-dashboard" class="btn btn-outline-light btn-sm ms-2">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Columna Principal - Scanner -->
            <div class="col-lg-8">
                <!-- Scanner -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-camera me-2"></i>Escáner de QR
                    </div>
                    <div class="card-body">
                        <div class="scanner-container">
                            <div id="reader"></div>
                            <div class="scan-area"></div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button id="startScanner" class="btn btn-success">
                                <i class="fas fa-play me-2"></i>Iniciar Cámara
                            </button>
                            <button id="stopScanner" class="btn btn-danger ms-2" style="display: none;">
                                <i class="fas fa-stop me-2"></i>Detener Cámara
                            </button>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instrucciones:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Presiona "Iniciar Cámara" para activar el escáner</li>
                                <li>Muestra el QR del estudiante en el área de escaneo</li>
                                <li>El sistema detectará automáticamente la información</li>
                                <li>Se registrará la entrada instantáneamente</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Información del Estudiante -->
                <div id="studentInfo" class="card" style="display: none;">
                    <div class="card-header">
                        <i class="fas fa-user-graduate me-2"></i>Información del Estudiante
                    </div>
                    <div class="card-body">
                        <div id="studentInfoContent">
                            <!-- Contenido dinámico -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="col-lg-4">
                <!-- Entrada Manual -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-keyboard me-2"></i>Entrada Manual
                    </div>
                    <div class="card-body">
                        <form id="manualForm">
                            <div class="mb-3">
                                <label for="personaInput" class="form-label">ID del Estudiante:</label>
                                <input type="text" class="form-control" id="personaInput" 
                                       placeholder="Ingresa el ID del estudiante" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check me-2"></i>Registrar Entrada
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas del Día
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number text-success"><?php echo $estadisticas['entradas_hoy']; ?></div>
                                <div class="stat-label">Total Entradas</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-warning"><?php echo $estadisticas['tardanzas_hoy']; ?></div>
                                <div class="stat-label">Tardanzas</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-primary"><?php echo $estadisticas['entradas_manana']; ?></div>
                                <div class="stat-label">Mañana</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-info"><?php echo $estadisticas['entradas_tarde']; ?></div>
                                <div class="stat-label">Tarde</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Jornada actual: <?php echo ucfirst($estadisticas['jornada_actual']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Entradas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list me-2"></i>Entradas de Hoy - <?php echo date('d/m/Y'); ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="entrance-list">
                            <?php if (empty($entradas_hoy)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-door-closed fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay entradas registradas hoy</h5>
                                <p class="text-muted">Los registros aparecerán aquí cuando se escaneen QR</p>
                            </div>
                            <?php else: ?>
                            <?php foreach ($entradas_hoy as $entrada): ?>
                            <div class="entrance-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($entrada['nom_user']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            ID: <?php echo $entrada['id_user']; ?> | 
                                            Grado: <?php echo $entrada['grado_nombre']; ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?php echo date('H:i', strtotime($entrada['hora_entrada'])); ?></div>
                                        <div class="mt-1">
                                            <span class="status-badge <?php echo $entrada['jornada'] === 'mañana' ? 'bg-primary' : 'bg-info'; ?> text-white me-2">
                                                <?php echo ucfirst($entrada['jornada']); ?>
                                            </span>
                                            <?php if ($entrada['tipo'] === 'late'): ?>
                                                <span class="status-badge status-late">Tardanza</span>
                                            <?php elseif ($entrada['tipo'] === 'normal'): ?>
                                                <span class="status-badge status-on-time">A tiempo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
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
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                document.getElementById('startScanner').style.display = 'none';
                document.getElementById('stopScanner').style.display = 'inline-block';
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
            
            procesarEntradaQR(decodedText);
            
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

        function procesarEntradaQR(qrContent) {
            try {
                const data = JSON.parse(qrContent);
                
                if (data.tipo === 'persona') {
                    mostrarResultadoEstudiante(data);
                    registrarEntradaInstitucion(data);
                } else {
                    showNotification('Este QR no es de un estudiante', 'error');
                }
                
            } catch (e) {
                console.error('Error procesando QR:', e);
                showNotification('Código QR inválido', 'error');
            }
        }

        function mostrarResultadoEstudiante(data) {
            const resultDiv = document.getElementById('studentInfo');
            const contentDiv = document.getElementById('studentInfoContent');
            
            const html = `
                <div class="student-info">
                    <h6><i class="fas fa-user-graduate me-2"></i>Estudiante Detectado</h6>
                    <div class="row">
                        <div class="col-6">
                            <strong>Nombre:</strong><br>
                            <span>${data.nom}</span>
                        </div>
                        <div class="col-6">
                            <strong>ID:</strong><br>
                            <span>${data.id}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <strong>Email:</strong><br>
                            <span>${data.em}</span>
                        </div>
                        <div class="col-6">
                            <strong>Grado:</strong><br>
                            <span>${data.gr || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="status-badge status-on-time">QR Válido</span>
                    </div>
                </div>
            `;
            
            contentDiv.innerHTML = html;
            resultDiv.style.display = 'block';
        }

        function registrarEntradaInstitucion(data) {
            const formData = new FormData();
            formData.append('id_user', data.id);
            
            fetch('index.php?action=registrar-entrada-institucion', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('Entrada registrada correctamente', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showNotification('Error: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al registrar entrada', 'error');
            });
        }

        // Formulario manual
        document.getElementById('manualForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('id_user', document.getElementById('personaInput').value);
            
            fetch('index.php?action=registrar-entrada-manual', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('Entrada registrada correctamente', 'success');
                    document.getElementById('personaInput').value = '';
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showNotification('Error: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al registrar entrada', 'error');
            });
        });

        function showNotification(mensaje, tipo) {
            const notification = document.createElement('div');
            notification.className = `notification ${tipo}`;
            notification.innerHTML = `
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${mensaje}
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 4000);
        }

        // Iniciar escáner automáticamente
        window.addEventListener('load', function() {
            setTimeout(() => {
                startScanner();
            }, 1000);
        });
    </script>
</body>
</html> 