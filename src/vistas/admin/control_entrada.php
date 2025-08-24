<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Entrada - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">🏫 Sistema de Eventos - ADMIN</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?accion=inicio">🏠 Dashboard</a>
                <a class="nav-link" href="index.php?accion=logout">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-4">
        
        <!-- Título -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h2>🚪 Control de Entrada</h2>
                        <p class="mb-0">Registra la entrada de estudiantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas simples -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>45</h3>
                        <p class="mb-0">✅ Entradas Hoy</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3>8</h3>
                        <p class="mb-0">⏰ Tardanzas</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3>3</h3>
                        <p class="mb-0">❌ Ausencias</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selector de jornada -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">⏰ Jornada</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary w-100" onclick="setJornada('mañana')">
                                    🌅 Mañana
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-info w-100" onclick="setJornada('tarde')">
                                    🌆 Tarde
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escáner QR -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">📹 Escáner QR</h5>
                    </div>
                    <div class="card-body">
                        <div id="reader" style="width: 100%; height: 300px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc;">
                            <div class="text-center">
                                <h4>📹 Cámara</h4>
                                <p>Haz clic en "Iniciar Escáner"</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success" onclick="iniciarEscanner()">
                                ▶️ Iniciar Escáner
                            </button>
                            <button class="btn btn-danger" onclick="detenerEscanner()">
                                ⏹️ Detener
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📋 Últimas Entradas</h5>
                    </div>
                    <div class="card-body">
                        <div id="ultimas_entradas">
                            <p class="text-muted">No hay entradas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="mt-3">
            <a href="index.php?accion=inicio" class="btn btn-secondary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>

    <script>
        let jornadaActual = 'mañana';
        let qrReader = null;

        function setJornada(jornada) {
            jornadaActual = jornada;
            alert(`Jornada: ${jornada}`);
        }
        
        function iniciarEscanner() {
            if (!qrReader) {
                qrReader = new Html5Qrcode("reader");
            }
            qrReader.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: 250
                },
                qrCodeMessage => {
                    // Enviar el código escaneado al backend por AJAX
                    registrarEntrada(qrCodeMessage, jornadaActual);
                    detenerEscanner();
                },
                errorMessage => {
                    // Puedes mostrar errores si quieres
                }
            );
        }
        
        function detenerEscanner() {
            if (qrReader) {
                qrReader.stop().then(() => {
                    // Limpia el área del escáner
                    document.getElementById('reader').innerHTML = `
                        <div class="text-center">
                            <h4>📹 Cámara</h4>
                            <p>Haz clic en "Iniciar Escáner"</p>
                        </div>
                    `;
                });
            }
        }
        
        function agregarEntrada(nombre, grado, hora) {
            const lista = document.getElementById('ultimas_entradas');
            const entrada = document.createElement('div');
            entrada.className = 'border-bottom pb-2 mb-2';
            entrada.innerHTML = `
                <strong>${nombre}</strong><br>
                <small class="text-muted">${hora} - ${grado}</small>
            `;
            
            lista.insertBefore(entrada, lista.firstChild);
        }
        
        function registrarEntrada(qrCode, jornada) {
            fetch('registrar_entrada.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ qr: qrCode, jornada: jornada })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    agregarEntrada(data.nombre, data.grado, data.hora);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => alert('Error al registrar la entrada.'));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>