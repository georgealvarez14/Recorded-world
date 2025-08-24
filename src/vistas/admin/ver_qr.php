<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Códigos QR - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h2>👁️ Ver Códigos QR</h2>
                        <p class="mb-0">Visualiza códigos QR generados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros simples -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🔍 Filtros</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_qr" class="form-label">Tipo:</label>
                                <select class="form-select" id="tipo_qr" onchange="filtrarQR()">
                                    <option value="">Todos</option>
                                    <option value="estudiante">Estudiantes</option>
                                    <option value="evento">Eventos</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grado" class="form-label">Grado:</label>
                                <select class="form-select" id="grado" onchange="filtrarQR()">
                                    <option value="">Todos</option>
                                    <option value="6">Grado 6</option>
                                    <option value="7">Grado 7</option>
                                    <option value="8">Grado 8</option>
                                    <option value="9">Grado 9</option>
                                    <option value="10">Grado 10</option>
                                    <option value="11">Grado 11</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de códigos QR -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📱 Códigos QR Generados</h5>
                    </div>
                    <div class="card-body">
                        <div id="lista_qr">
                            <?php
                            // Función para obtener archivos QR
                            function getQRFiles($directory) {
                                $files = [];
                                if (is_dir($directory)) {
                                    $items = scandir($directory);
                                    foreach ($items as $item) {
                                        if ($item != '.' && $item != '..') {
                                            $path = $directory . '/' . $item;
                                            if (is_dir($path)) {
                                                $files = array_merge($files, getQRFiles($path));
                                            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'png') {
                                                $files[] = $path;
                                            }
                                        }
                                    }
                                }
                                return $files;
                            }

                            // Obtener QR de estudiantes
                            $qr_estudiantes_dir = '../uploads/qr_estudiantes';
                            $qr_eventos_dir = '../uploads/qr_eventos';
                            
                            $estudiantes_qr = getQRFiles($qr_estudiantes_dir);
                            $eventos_qr = getQRFiles($qr_eventos_dir);
                            
                            $todos_qr = array_merge($estudiantes_qr, $eventos_qr);
                            
                            if (empty($todos_qr)) {
                                echo '<div class="alert alert-info">No hay códigos QR generados. Ve a "Generar QR" para crear algunos.</div>';
                            } else {
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-striped">';
                                echo '<thead><tr><th>Tipo</th><th>Nombre</th><th>Información</th><th>Archivo</th><th>Acciones</th></tr></thead>';
                                echo '<tbody>';
                                
                                foreach ($todos_qr as $qr_file) {
                                    $filename = basename($qr_file);
                                    $relative_path = str_replace('../', '', $qr_file);
                                    
                                    // Extraer información del nombre del archivo
                                    if (strpos($filename, 'estudiante_') === 0) {
                                        $tipo = 'Estudiante';
                                        $tipo_badge = '<span class="badge bg-success">Estudiante</span>';
                                        
                                        // Extraer ID y nombre del archivo
                                        $parts = explode('_', $filename);
                                        $id = $parts[1] ?? '';
                                        $nombre = isset($parts[2]) ? str_replace('.png', '', $parts[2]) : '';
                                        
                                        // Buscar información en la base de datos
                                        $stmt = $pdo->prepare("SELECT * FROM persona WHERE id_user = ?");
                                        $stmt->execute([$id]);
                                        $persona = $stmt->fetch();
                                        
                                        if ($persona) {
                                            $info = "Grado: {$persona['cod_grado']} - ID: {$persona['id_user']}";
                                            $nombre = $persona['nom_user'];
                                        } else {
                                            $info = "ID: $id";
                                        }
                                        
                                        $download_url = "index.php?accion=descargar_qr&tipo=estudiante&id=$id&nombre=" . urlencode($nombre);
                                        
                                    } elseif (strpos($filename, 'evento_') === 0) {
                                        $tipo = 'Evento';
                                        $tipo_badge = '<span class="badge bg-info">Evento</span>';
                                        
                                        // Extraer código y nombre del archivo
                                        $parts = explode('_', $filename);
                                        $codigo = $parts[1] ?? '';
                                        $nombre = isset($parts[2]) ? str_replace('.png', '', $parts[2]) : '';
                                        
                                        // Buscar información en la base de datos
                                        $stmt = $pdo->prepare("SELECT * FROM evento WHERE cod_evento = ?");
                                        $stmt->execute([$codigo]);
                                        $evento = $stmt->fetch();
                                        
                                        if ($evento) {
                                            $info = "Fecha: {$evento['fecha_inicio']} - Hora: {$evento['hora']}";
                                            $nombre = $evento['nom_evento'];
                                        } else {
                                            $info = "Código: $codigo";
                                        }
                                        
                                        $download_url = "index.php?accion=descargar_qr&tipo=evento&id=$codigo&nombre=" . urlencode($nombre);
                                    } else {
                                        continue; // Saltar archivos que no reconocemos
                                    }
                                    
                                    echo '<tr>';
                                    echo '<td>' . $tipo_badge . '</td>';
                                    echo '<td>' . htmlspecialchars($nombre) . '</td>';
                                    echo '<td>' . htmlspecialchars($info) . '</td>';
                                    echo '<td><small class="text-muted">' . htmlspecialchars($filename) . '</small></td>';
                                    echo '<td>';
                                    echo '<a href="' . $download_url . '" class="btn btn-sm btn-success">📥 Descargar</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                
                                echo '</tbody></table>';
                                echo '</div>';
                            }
                            ?>
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
        function filtrarQR() {
            const tipo = document.getElementById('tipo_qr').value;
            const grado = document.getElementById('grado').value;
            
            // Recargar la página con filtros
            let url = 'index.php?accion=ver_qr';
            if (tipo) url += '&tipo=' + tipo;
            if (grado) url += '&grado=' + grado;
            
            window.location.href = url;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 