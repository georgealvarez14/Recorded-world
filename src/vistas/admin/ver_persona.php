<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Persona - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }

        .info-value {
            color: #6c757d;
        }

        .badge-custom {
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #dee2e6;
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            border: 4px solid #dee2e6;
        }

        .qr-code {
            max-width: 150px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #495057;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .section-divider {
            border-top: 2px solid #dee2e6;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user me-2"></i>Detalles de Persona</h1>
                <div>
                    <a href="index.php?accion=crud_personas" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <a href="index.php?accion=inicio" class="btn btn-outline-light btn-sm ms-2">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Información Principal -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-circle me-2"></i>Información Personal
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="info-row d-flex">
                                    <span class="info-label">ID:</span>
                                    <span class="info-value"><?php echo $persona['id_user']; ?></span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Nombre Completo:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($persona['nom_user']); ?></span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($persona['correo_user']); ?></span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Teléfono:</span>
                                    <span class="info-value"><?php echo $persona['telef_user'] ? $persona['telef_user'] : 'No registrado'; ?></span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Tipo de Persona:</span>
                                    <span class="info-value">
                                        <?php 
                                        $tipo_badges = [
                                            'ADM' => '<span class="badge-custom bg-danger">Administrador</span>',
                                            'DOC' => '<span class="badge-custom bg-primary">Docente</span>',
                                            'EST' => '<span class="badge-custom bg-success">Estudiante</span>',
                                            'ACU' => '<span class="badge-custom bg-warning text-dark">Acudiente</span>'
                                        ];
                                        echo $tipo_badges[$persona['tipo_persona']] ?? $persona['tipo_persona'];
                                        ?>
                                    </span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Ciudad:</span>
                                    <span class="info-value">
                                        <?php 
                                        if ($persona['ciudad']) {
                                            $stmt = $pdo->prepare("SELECT descripcion FROM ciudad WHERE cod_ciudad = ?");
                                            $stmt->execute([$persona['ciudad']]);
                                            $ciudad = $stmt->fetch();
                                            echo $ciudad ? $ciudad['descripcion'] : $persona['ciudad'];
                                        } else {
                                            echo 'No registrada';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <?php if ($persona['tipo_persona'] === 'EST'): ?>
                                <div class="info-row d-flex">
                                    <span class="info-label">Grado:</span>
                                    <span class="info-value">
                                        <?php 
                                        if ($persona['cod_grado']) {
                                            $stmt = $pdo->prepare("SELECT descripcion FROM grado WHERE cod_grado = ?");
                                            $stmt->execute([$persona['cod_grado']]);
                                            $grado = $stmt->fetch();
                                            echo $grado ? $grado['descripcion'] : $persona['cod_grado'];
                                        } else {
                                            echo 'No asignado';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Grupo:</span>
                                    <span class="info-value"><?php echo $persona['grupo'] ? $persona['grupo'] : 'No asignado'; ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="info-row d-flex">
                                    <span class="info-label">Fecha de Creación:</span>
                                    <span class="info-value"><?php echo $persona['fecha_creacion'] ? date('d/m/Y H:i', strtotime($persona['fecha_creacion'])) : 'No registrada'; ?></span>
                                </div>
                                <div class="info-row d-flex">
                                    <span class="info-label">Última Edición:</span>
                                    <span class="info-value"><?php echo $persona['fecha_edicion'] ? date('d/m/Y H:i', strtotime($persona['fecha_edicion'])) : 'No registrada'; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php if (isset($persona['foto_persona']) && $persona['foto_persona']): ?>
                                    <img src="<?php echo $persona['foto_persona']; ?>" alt="Foto de perfil" class="profile-photo">
                                <?php else: ?>
                                    <div class="photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($persona['codigo_qr']) && $persona['codigo_qr']): ?>
                                    <div class="mt-3">
                                        <img src="<?php echo $persona['codigo_qr']; ?>" alt="Código QR" class="qr-code">
                                        <p class="text-muted mt-2 small">Código QR</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Estadísticas -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <?php
                            // Eventos participados
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM participante WHERE id_user = ?");
                            $stmt->execute([$persona['id_user']]);
                            $eventos_participados = $stmt->fetch()['total'];
                            ?>
                            <div class="stat-item">
                                <div class="stat-number text-primary"><?php echo $eventos_participados; ?></div>
                                <div class="stat-label">Eventos Participados</div>
                            </div>

                            <?php
                            // Eventos asistidos
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM registro_participante WHERE id_user = ?");
                            $stmt->execute([$persona['id_user']]);
                            $eventos_asistidos = $stmt->fetch()['total'];
                            ?>
                            <div class="stat-item">
                                <div class="stat-number text-success"><?php echo $eventos_asistidos; ?></div>
                                <div class="stat-label">Eventos Asistidos</div>
                            </div>

                            <?php if ($persona['tipo_persona'] === 'EST'): ?>
                            <?php
                            // Entradas a la institución
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM entrada_institucion WHERE id_user = ?");
                            $stmt->execute([$persona['id_user']]);
                            $entradas_institucion = $stmt->fetch()['total'];
                            ?>
                            <div class="stat-item">
                                <div class="stat-number text-info"><?php echo $entradas_institucion; ?></div>
                                <div class="stat-label">Entradas Registradas</div>
                            </div>

                            <?php
                            // Tardanzas
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM entrada_institucion WHERE id_user = ? AND tipo_entrada = 'tardanza'");
                            $stmt->execute([$persona['id_user']]);
                            $tardanzas = $stmt->fetch()['total'];
                            ?>
                            <div class="stat-item">
                                <div class="stat-number text-warning"><?php echo $tardanzas; ?></div>
                                <div class="stat-label">Tardanzas</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tools me-2"></i>Acciones
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?accion=editar_persona_admin&id=<?php echo $persona['id_user']; ?>" 
                               class="btn btn-primary btn-action">
                                <i class="fas fa-edit"></i>Editar Persona
                            </a>
                            <?php if ($persona['tipo_persona'] === 'EST' && !isset($persona['codigo_qr'])): ?>
                            <a href="index.php?accion=generar_qr_estudiante&id=<?php echo $persona['id_user']; ?>" 
                               class="btn btn-success btn-action">
                                <i class="fas fa-qrcode"></i>Generar QR
                            </a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-danger btn-action" 
                                    onclick="confirmarEliminar(<?php echo $persona['id_user']; ?>, '<?php echo htmlspecialchars($persona['nom_user']); ?>')">
                                <i class="fas fa-trash"></i>Eliminar Persona
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($persona['tipo_persona'] === 'EST'): ?>
        <!-- Información del Acudiente -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-friends me-2"></i>Información del Acudiente
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row d-flex">
                            <span class="info-label">Nombre del Acudiente:</span>
                            <span class="info-value"><?php echo htmlspecialchars($persona['nom_acudiente'] ?? 'No registrado'); ?></span>
                        </div>
                        <div class="info-row d-flex">
                            <span class="info-label">CC del Acudiente:</span>
                            <span class="info-value"><?php echo $persona['cc_acudiente'] ?? 'No registrado'; ?></span>
                        </div>
                        <div class="info-row d-flex">
                            <span class="info-label">Email del Acudiente:</span>
                            <span class="info-value"><?php echo htmlspecialchars($persona['correo_acudiente'] ?? 'No registrado'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row d-flex">
                            <span class="info-label">Teléfono Principal:</span>
                            <span class="info-value"><?php echo $persona['telef_acudiente'] ?? 'No registrado'; ?></span>
                        </div>
                        <div class="info-row d-flex">
                            <span class="info-label">Teléfono Secundario:</span>
                            <span class="info-value"><?php echo $persona['telef_acudiente_dos'] ?? 'No registrado'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Historial de Entradas a la Institución -->
        <?php if ($persona['tipo_persona'] === 'EST'): ?>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-door-open me-2"></i>Historial de Entradas a la Institución
            </div>
            <div class="card-body">
                <?php
                $stmt = $pdo->prepare("
                    SELECT * FROM entrada_institucion 
                    WHERE id_user = ? 
                    ORDER BY fecha_entrada DESC, hora_entrada DESC 
                    LIMIT 10
                ");
                $stmt->execute([$persona['id_user']]);
                $entradas = $stmt->fetchAll();
                
                if (empty($entradas)) {
                    echo '<p class="text-muted text-center">No hay registros de entrada aún.</p>';
                } else {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-striped">';
                    echo '<thead><tr><th>Fecha</th><th>Hora</th><th>Jornada</th><th>Tipo</th><th>Observaciones</th></tr></thead>';
                    echo '<tbody>';
                    foreach ($entradas as $entrada) {
                        $tipo_badge = [
                            'normal' => '<span class="badge bg-success">A tiempo</span>',
                            'tardanza' => '<span class="badge bg-warning text-dark">Tardanza</span>',
                            'ausente' => '<span class="badge bg-danger">Ausente</span>'
                        ];
                        echo '<tr>';
                        echo '<td>' . date('d/m/Y', strtotime($entrada['fecha_entrada'])) . '</td>';
                        echo '<td>' . $entrada['hora_entrada'] . '</td>';
                        echo '<td>' . ucfirst($entrada['jornada']) . '</td>';
                        echo '<td>' . $tipo_badge[$entrada['tipo_entrada']] . '</td>';
                        echo '<td>' . ($entrada['observaciones'] ?? '-') . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table></div>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Eventos Participados -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-alt me-2"></i>Eventos Participados
            </div>
            <div class="card-body">
                <?php
                try {
                    // Consulta simplificada para eventos participados
                    $stmt = $pdo->prepare("
                        SELECT e.cod_evento, e.nom_evento, e.descripcion, e.fecha_inicio, e.hora, e.ubicacion, e.materia,
                               rp.fecha_llegada
                        FROM evento e 
                        INNER JOIN participante p ON e.cod_evento = p.cod_evento 
                        LEFT JOIN registro_participante rp ON e.cod_evento = rp.cod_evento AND p.id_user = rp.id_user
                        WHERE p.id_user = ?
                        ORDER BY e.fecha_inicio DESC
                    ");
                    $stmt->execute([$persona['id_user']]);
                    $eventos = $stmt->fetchAll();
                    
                    if (empty($eventos)) {
                        echo '<p class="text-muted text-center">No ha participado en ningún evento aún.</p>';
                    } else {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead><tr><th>Evento</th><th>Fecha</th><th>Hora</th><th>Ubicación</th><th>Asistencia</th><th>Materia</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($eventos as $evento) {
                            $asistencia = $evento['fecha_llegada'] ? 
                                '<span class="badge bg-success">✅ Asistió</span>' : 
                                '<span class="badge bg-secondary">❌ No asistió</span>';
                            
                            // Obtener nombre de la materia si existe
                            $materia_nombre = 'N/A';
                            if ($evento['materia']) {
                                $stmt_materia = $pdo->prepare("SELECT descripcion FROM materias WHERE cod_categoria = ?");
                                $stmt_materia->execute([$evento['materia']]);
                                $materia = $stmt_materia->fetch();
                                if ($materia) {
                                    $materia_nombre = $materia['descripcion'];
                                }
                            }
                            
                            echo '<tr>';
                            echo '<td><strong>' . htmlspecialchars($evento['nom_evento']) . '</strong><br>';
                            echo '<small class="text-muted">' . htmlspecialchars($evento['descripcion']) . '</small></td>';
                            echo '<td>' . date('d/m/Y', strtotime($evento['fecha_inicio'])) . '</td>';
                            echo '<td>' . $evento['hora'] . '</td>';
                            echo '<td>' . htmlspecialchars($evento['ubicacion']) . '</td>';
                            echo '<td>' . $asistencia . '</td>';
                            echo '<td>' . $materia_nombre . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table></div>';
                    }
                } catch (Exception $e) {
                    echo '<p class="text-muted text-center">Error al cargar eventos: ' . $e->getMessage() . '</p>';
                }
                ?>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="text-center mt-4 mb-4">
            <a href="index.php?accion=editar_persona_admin&id=<?php echo $persona['id_user']; ?>" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-edit me-2"></i>Editar Persona
            </a>
            <a href="index.php?accion=crud_personas" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminar(id, nombre) {
            if (confirm(`¿Estás seguro de que quieres eliminar a "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                window.location.href = `index.php?accion=eliminar_persona&id=${id}`;
            }
        }
    </script>
</body>
</html> 