<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia - Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Sistema de Eventos - ESTUDIANTE
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=dashboard_estudiante">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?accion=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h2><i class="fas fa-qrcode"></i> Registrar Asistencia</h2>
                        <p class="mb-0">Escanea el código QR del evento para registrar tu asistencia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Scanner QR -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-camera"></i> Escáner QR</h5>
                    </div>
                    <div class="card-body">
                        <div id="reader" style="width: 100%; height: 400px;"></div>
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="startScanner()">
                                <i class="fas fa-play"></i> Iniciar Escáner
                            </button>
                            <button class="btn btn-secondary" onclick="stopScanner()">
                                <i class="fas fa-stop"></i> Detener Escáner
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información</h5>
                    </div>
                    <div class="card-body">
                        <h6><i class="fas fa-list-check"></i> Instrucciones:</h6>
                        <ol>
                            <li>Haz clic en "Iniciar Escáner"</li>
                            <li>Permite acceso a la cámara</li>
                            <li>Apunta hacia el código QR del evento</li>
                            <li>Tu asistencia se registrará automáticamente</li>
                        </ol>
                        
                        <hr>
                        
                        <h6><i class="fas fa-exclamation-triangle"></i> Notas importantes:</h6>
                        <ul>
                            <li>Solo puedes registrar asistencia en eventos de hoy</li>
                            <li>Debes estar inscrito en el evento</li>
                            <li>La asistencia se registra una sola vez</li>
                            <li>Mantén el código QR estable para mejor lectura</li>
                        </ul>
                        
                        <hr>
                        
                        <h6><i class="fas fa-calendar-day"></i> Mis Eventos de Hoy:</h6>
                        <?php
                        $stmt = $pdo->prepare("
                            SELECT e.* 
                            FROM evento e 
                            INNER JOIN participante p ON e.cod_evento = p.cod_evento 
                            WHERE p.id_user = ? AND e.fecha_inicio = CURDATE()
                            ORDER BY e.hora
                        ");
                        $stmt->execute([$_SESSION['usuario_id']]);
                        $eventos_hoy = $stmt->fetchAll();
                        
                        if (empty($eventos_hoy)) {
                            echo '<p class="text-muted">No tienes eventos programados para hoy.</p>';
                        } else {
                            echo '<div class="list-group">';
                            foreach ($eventos_hoy as $evento) {
                                echo '<div class="list-group-item">';
                                echo '<h6 class="mb-1">' . htmlspecialchars($evento['nom_evento']) . '</h6>';
                                echo '<p class="mb-1"><small>Hora: ' . $evento['hora'] . ' | Ubicación: ' . ($evento['ubicacion'] ?? 'Por definir') . '</small></p>';
                                echo '<small class="text-muted">Código: ' . $evento['cod_evento'] . '</small>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario manual (alternativo) -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-keyboard"></i> Registro Manual</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?accion=registrar_asistencia_manual">
                            <div class="mb-3">
                                <label for="codigo_evento" class="form-label">Código del Evento:</label>
                                <input type="text" class="form-control" id="codigo_evento" name="codigo_evento" 
                                       placeholder="Ej: EVT001" required>
                                <div class="form-text">Ingresa el código del evento si no puedes escanear el QR</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-check"></i> Registrar Asistencia Manual
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=dashboard_estudiante" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;
        
        function startScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { 
                    fps: 10, 
                    qrbox: {width: 250, height: 250},
                    aspectRatio: 1.0
                }
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
        
        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }
        }
        
        function onScanSuccess(decodedText, decodedResult) {
            // Detener el escáner
            stopScanner();
            
            // Mostrar mensaje de éxito
            alert('Código QR detectado: ' + decodedText);
            
            // Enviar el código al servidor
            window.location.href = `index.php?accion=registrar_asistencia_qr&codigo=${decodedText}`;
        }
        
        function onScanFailure(error) {
            // Manejar errores de escaneo (opcional)
            console.warn(`Error de escaneo: ${error}`);
        }
        
        // Iniciar escáner automáticamente al cargar la página
        window.onload = function() {
            setTimeout(startScanner, 1000);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 