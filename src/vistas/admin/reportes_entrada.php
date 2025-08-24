<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Entrada - Administrador</title>
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
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h2>📊 Reportes de Entrada</h2>
                        <p class="mb-0">Reportes de entradas y tardanzas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros simples -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📅 Filtros</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                                <input type="date" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                                <input type="date" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="jornada" class="form-label">Jornada:</label>
                                <select class="form-select" id="jornada">
                                    <option value="">Todas</option>
                                    <option value="mañana">Mañana</option>
                                    <option value="tarde">Tarde</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="generarReporte()">
                                📊 Generar Reporte
                            </button>
                            <button class="btn btn-success" onclick="exportarExcel()">
                                📥 Exportar Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen estadístico -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>45</h3>
                        <p class="mb-0">✅ A Tiempo</p>
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

        <!-- Tabla de entradas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Registro de Entradas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estudiante</th>
                                        <th>Grado</th>
                                        <th>Hora Entrada</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2024-01-15</td>
                                        <td>Juan Pérez</td>
                                        <td>6°A</td>
                                        <td>6:10 AM</td>
                                        <td><span class="badge bg-success">A tiempo</span></td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15</td>
                                        <td>María García</td>
                                        <td>7°B</td>
                                        <td>6:25 AM</td>
                                        <td><span class="badge bg-warning">Tardanza</span></td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15</td>
                                        <td>Carlos López</td>
                                        <td>8°C</td>
                                        <td>12:15 PM</td>
                                        <td><span class="badge bg-success">A tiempo</span></td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15</td>
                                        <td>Ana Rodríguez</td>
                                        <td>9°A</td>
                                        <td>12:45 PM</td>
                                        <td><span class="badge bg-warning">Tardanza</span></td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15</td>
                                        <td>Luis Martínez</td>
                                        <td>10°B</td>
                                        <td>-</td>
                                        <td><span class="badge bg-danger">Ausente</span></td>
                                    </tr>
                                </tbody>
                            </table>
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
        function generarReporte() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;
            const jornada = document.getElementById('jornada').value;
            
            alert(`Generando reporte: ${fechaInicio} a ${fechaFin}, Jornada: ${jornada}`);
        }
        
        function exportarExcel() {
            alert('Exportando a Excel...');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 