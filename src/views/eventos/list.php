<?php
// Obtener eventos de forma simplificada
try {
                $sql = "SELECT * FROM evento ORDER BY fecha_inicio DESC";
    $eventos = $db->fetchAll($sql);
} catch (Exception $e) {
    $eventos = [];
    error_log("Error obteniendo eventos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt me-2"></i>Sistema de Gestión
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home me-1"></i>Inicio
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
                    <i class="fas fa-calendar-alt text-primary me-2"></i>Eventos
                </h1>
                <p class="text-muted mb-0">Gestiona y visualiza todos los eventos del sistema</p>
            </div>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ADM'): ?>
            <a href="index.php?action=eventos&controller=create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nuevo Evento
            </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if (empty($eventos)): ?>
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
            <h3 class="text-muted mb-3">No hay eventos registrados</h3>
            <p class="text-muted mb-4">Aún no se han creado eventos en el sistema.</p>
            <?php if ($_SESSION['user_type'] === 'ADM'): ?>
            <a href="index.php?action=eventos&controller=create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Primer Evento
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        
        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchEvent" placeholder="Buscar eventos...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="">Todos los estados</option>
                    <option value="upcoming">Próximos</option>
                    <option value="active">Activos</option>
                    <option value="past">Pasados</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterType">
                    <option value="">Todos los tipos</option>
                    <option value="academic">Académico</option>
                    <option value="cultural">Cultural</option>
                    <option value="sports">Deportivo</option>
                </select>
            </div>
        </div>

        <div class="row" id="eventsContainer">
            <?php foreach ($eventos as $evento): ?>
            <?php
            // Determinar el estado del evento
            $eventDate = isset($evento['fecha_evento']) ? $evento['fecha_evento'] : 
                        (isset($evento['fecha_inicio']) ? $evento['fecha_inicio'] : '');
            $today = date('Y-m-d');
            $status = '';
            $statusClass = '';
            
            if ($eventDate) {
                if ($eventDate < $today) {
                    $status = 'Pasado';
                    $statusClass = 'status-past';
                } elseif ($eventDate == $today) {
                    $status = 'Hoy';
                    $statusClass = 'status-active';
                } else {
                    $status = 'Próximo';
                    $statusClass = 'status-upcoming';
                }
            }
            ?>
            <div class="col-md-6 col-lg-4 mb-4 event-item">
                <div class="card event-card h-100 position-relative">
                    <?php if ($status): ?>
                    <span class="event-status <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                    <?php endif; ?>
                    
                    <?php if (!empty($evento['foto_evento'])): ?>
                    <img src="<?php echo htmlspecialchars($evento['foto_evento']); ?>" 
                         class="card-img-top" alt="Foto del evento" 
                         style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" 
                         style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-calendar-alt text-white" style="font-size: 3rem;"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">
                            <?php echo htmlspecialchars($evento['nombre_evento'] ?? $evento['nom_evento'] ?? 'Evento sin nombre'); ?>
                        </h5>
                        
                        <p class="card-text flex-grow-1">
                            <?php echo htmlspecialchars($evento['descripcion'] ?? 'Sin descripción disponible'); ?>
                        </p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Fecha:
                                </small><br>
                                <strong>
                                    <?php 
                                    if ($eventDate) {
                                        echo date('d/m/Y', strtotime($eventDate));
                                    } else {
                                        echo 'No definida';
                                    }
                                    ?>
                                </strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Hora:
                                </small><br>
                                <strong>
                                    <?php 
                                    $hora = $evento['hora'] ?? $evento['hora_evento'] ?? '';
                                    echo $hora ? date('H:i', strtotime($hora)) : 'No definida';
                                    ?>
                                </strong>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>Ubicación:
                                </small><br>
                                <strong>
                                    <?php echo htmlspecialchars($evento['lugar_evento'] ?? $evento['ubicacion'] ?? 'No definida'); ?>
                                </strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>Aforo:
                                </small><br>
                                <strong>
                                    <?php echo $evento['aforo_max'] ?? 'Sin límite'; ?> personas
                                </strong>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-grid gap-2">
                                <a href="index.php?action=eventos&controller=view&id=<?php echo $evento['id_evento'] ?? $evento['cod_evento']; ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Ver Detalles
                                </a>
                                
                                <?php if ($_SESSION['user_type'] === 'ADM'): ?>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=eventos&controller=edit&id=<?php echo $evento['id_evento'] ?? $evento['cod_evento']; ?>" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteEvent(<?php echo $evento['id_evento'] ?? $evento['cod_evento']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funcionalidad de búsqueda y filtros
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchEvent');
            const filterStatus = document.getElementById('filterStatus');
            const filterType = document.getElementById('filterType');
            const eventsContainer = document.getElementById('eventsContainer');
            const eventItems = document.querySelectorAll('.event-item');

            function filterEvents() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusFilter = filterStatus.value;
                const typeFilter = filterType.value;

                eventItems.forEach(item => {
                    const title = item.querySelector('.card-title').textContent.toLowerCase();
                    const description = item.querySelector('.card-text').textContent.toLowerCase();
                    const status = item.querySelector('.event-status')?.textContent.toLowerCase() || '';

                    const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                    const matchesStatus = !statusFilter || status.includes(statusFilter);
                    const matchesType = !typeFilter; // Por ahora no filtramos por tipo

                    if (matchesSearch && matchesStatus && matchesType) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterEvents);
            filterStatus.addEventListener('change', filterEvents);
            filterType.addEventListener('change', filterEvents);
        });

        function deleteEvent(eventId) {
            if (confirm('¿Estás seguro de que quieres eliminar este evento?')) {
                // Aquí iría la lógica para eliminar el evento
                alert('Funcionalidad de eliminación en desarrollo');
            }
        }
    </script>
</body>
</html> 