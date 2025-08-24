<?php

// Iniciar sesión
session_start();

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener la acción desde la URL
$accion = $_GET['accion'] ?? 'inicio';

// ===== SISTEMA DE RUTAS SIMPLIFICADO =====
switch ($accion) {
    
    // === PÁGINA PRINCIPAL ===
    case 'inicio':
        if (isset($_SESSION['usuario_id'])) {
            // Usuario logueado - mostrar dashboard según tipo
            if ($_SESSION['usuario_tipo'] === 'ADM') {
                include '../src/vistas/admin/dashboard.php';
            } elseif ($_SESSION['usuario_tipo'] === 'DOC') {
                include '../src/vistas/docente/dashboard.php';
            } elseif ($_SESSION['usuario_tipo'] === 'ACU') {
                include '../src/vistas/acudiente/dashboard.php';
            } else {
                include '../src/vistas/dashboard.php';
            }
        } else {
            // Usuario no logueado - mostrar página principal
            include '../src/vistas/pagina_principal.php';
        }
        break;
    
    // === LOGIN ===
    case 'login':
        include '../src/vistas/login.php';
        break;
    
    case 'procesar_login':
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $stmt = $pdo->prepare("SELECT * FROM persona WHERE correo_user = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && $usuario['contrasena_user'] === $password) {
            $_SESSION['usuario_id'] = $usuario['id_user'];
            $_SESSION['usuario_nombre'] = $usuario['nom_user'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_persona'];
            $_SESSION['usuario_email'] = $usuario['correo_user'];
            
            header('Location: index.php?accion=inicio');
            exit;
        } else {
            $_SESSION['error'] = 'Email o contraseña incorrectos';
            header('Location: index.php?accion=login');
            exit;
        }
        break;
    
    // === LOGOUT ===
    case 'logout':
        session_destroy();
        header('Location: index.php');
        exit;
        break;
    
    // === GESTIÓN DE PERSONAS ===
    case 'personas':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/personas/lista.php';
        break;
    
    case 'crear_persona':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/personas/crear.php';
        break;
    
    case 'guardar_persona':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $password = $_POST['password'] ?? '123456';
        
        $stmt = $pdo->prepare("INSERT INTO persona (nom_user, correo_user, contrasena_user, tipo_persona) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $password, $tipo]);
        
        header('Location: index.php?accion=personas');
        exit;
        break;
    
    case 'editar_persona':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        $id = $_GET['id'] ?? 0;
        include '../src/vistas/personas/editar.php';
        break;
    
    case 'actualizar_persona':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE persona SET nom_user = ?, correo_user = ?, tipo_persona = ? WHERE id_user = ?");
        $stmt->execute([$nombre, $email, $tipo, $id]);
        
        header('Location: index.php?accion=personas');
        exit;
        break;
    
    case 'eliminar_persona':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM persona WHERE id_user = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=personas');
        exit;
        break;
    
    // === GESTIÓN DE EVENTOS ===
    case 'eventos':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/eventos/lista.php';
        break;
    
    case 'crear_evento':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/eventos/crear.php';
        break;
    
    case 'guardar_evento':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO eventos (nombre_evento, fecha_evento, descripcion_evento) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $fecha, $descripcion]);
        
        header('Location: index.php?accion=eventos');
        exit;
        break;
    
    // === SISTEMA QR ===
    case 'generar_qr':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/generar_qr.php';
        break;
    
    case 'ver_qr':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/ver_qr.php';
        break;
    
    // === PANEL DE ADMINISTRACIÓN ===
    
    // Gestión de Personas
    case 'crud_personas':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crud_personas.php';
        break;
    
    case 'crear_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crear_persona.php';
        break;
    
    case 'editar_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/editar_persona.php';
        break;
    
    case 'ver_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/ver_persona.php';
        break;
    
    // Gestión de Eventos
    case 'crud_eventos':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crud_eventos.php';
        break;
    
    case 'crear_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crear_evento.php';
        break;
    
    case 'editar_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/editar_evento.php';
        break;
    
    case 'ver_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/ver_evento.php';
        break;
    
    // Gestión de Materias
    case 'crud_materias':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crud_materias.php';
        break;
    
    case 'crear_materia_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crear_materia.php';
        break;
    
    case 'editar_materia_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/editar_materia.php';
        break;
    
    // Gestión de Grados
    case 'crud_grados':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crud_grados.php';
        break;
    
    case 'crear_grado_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crear_grado.php';
        break;
    
    case 'editar_grado_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/editar_grado.php';
        break;
    
    // Gestión de Ciudades
    case 'crud_ciudades':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crud_ciudades.php';
        break;
    
    case 'crear_ciudad_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/crear_ciudad.php';
        break;
    
    case 'editar_ciudad_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/editar_ciudad.php';
        break;
    
    // Control de Entrada
    case 'control_entrada':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/control_entrada.php';
        break;
    
    case 'reportes_entrada':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/reportes_entrada.php';
        break;
    
    // Gestión de Peticiones de Eventos de Docentes
    case 'peticiones_eventos_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/admin/peticiones_eventos.php';
        break;
    
    case 'aprobar_peticion_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $peticion_id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("UPDATE peticiones_evento SET estado = 'APROBADA' WHERE id = ?");
        $stmt->execute([$peticion_id]);
        
        header('Location: index.php?accion=peticiones_eventos_admin');
        exit;
        break;
    
    case 'rechazar_peticion_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $peticion_id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("UPDATE peticiones_evento SET estado = 'RECHAZADA' WHERE id = ?");
        $stmt->execute([$peticion_id]);
        
        header('Location: index.php?accion=peticiones_eventos_admin');
        exit;
        break;
    
    // === PROCESAMIENTO DE FORMULARIOS ADMIN ===
    
    // Personas
    case 'guardar_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $password = $_POST['password'] ?? '123456';
        $grupo = $_POST['grupo'] ?? null;
        $grado = $_POST['grado'] ?? null;
        $ciudad = $_POST['ciudad'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        
        $stmt = $pdo->prepare("INSERT INTO persona (nom_user, correo_user, contrasena_user, tipo_persona, grupo, cod_grado, ciudad, telef_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $password, $tipo, $grupo, $grado, $ciudad, $telefono]);
        
        header('Location: index.php?accion=crud_personas');
        exit;
        break;
    
    case 'actualizar_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $grupo = $_POST['grupo'] ?? null;
        $grado = $_POST['grado'] ?? null;
        $ciudad = $_POST['ciudad'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        
        $stmt = $pdo->prepare("UPDATE persona SET nom_user = ?, correo_user = ?, tipo_persona = ?, grupo = ?, cod_grado = ?, ciudad = ?, telef_user = ? WHERE id_user = ?");
        $stmt->execute([$nombre, $email, $tipo, $grupo, $grado, $ciudad, $telefono, $id]);
        
        header('Location: index.php?accion=crud_personas');
        exit;
        break;
    
    case 'eliminar_persona_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM persona WHERE id_user = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=crud_personas');
        exit;
        break;
    
    // Eventos
    case 'guardar_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $materia = $_POST['materia'] ?? null;
        $ubicacion = $_POST['ubicacion'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO evento (nom_evento, fecha_inicio, descripcion_evento, cod_materia, ubicacion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $fecha, $descripcion, $materia, $ubicacion]);
        
        header('Location: index.php?accion=crud_eventos');
        exit;
        break;
    
    case 'actualizar_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $materia = $_POST['materia'] ?? null;
        $ubicacion = $_POST['ubicacion'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE evento SET nom_evento = ?, fecha_inicio = ?, descripcion_evento = ?, cod_materia = ?, ubicacion = ? WHERE cod_evento = ?");
        $stmt->execute([$nombre, $fecha, $descripcion, $materia, $ubicacion, $id]);
        
        header('Location: index.php?accion=crud_eventos');
        exit;
        break;
    
    case 'eliminar_evento_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM evento WHERE cod_evento = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=crud_eventos');
        exit;
        break;
    
    // Materias
    case 'guardar_materia_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO materia (nom_materia, descripcion_materia) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        
        header('Location: index.php?accion=crud_materias');
        exit;
        break;
    
    case 'actualizar_materia_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE materia SET nom_materia = ?, descripcion_materia = ? WHERE cod_materia = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
        
        header('Location: index.php?accion=crud_materias');
        exit;
        break;
    
    case 'eliminar_materia_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM materia WHERE cod_materia = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=crud_materias');
        exit;
        break;
    
    // Grados
    case 'guardar_grado_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO grado (nom_grado, descripcion_grado) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        
        header('Location: index.php?accion=crud_grados');
        exit;
        break;
    
    case 'actualizar_grado_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE grado SET nom_grado = ?, descripcion_grado = ? WHERE cod_grado = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
        
        header('Location: index.php?accion=crud_grados');
        exit;
        break;
    
    case 'eliminar_grado_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM grado WHERE cod_grado = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=crud_grados');
        exit;
        break;
    
    // Ciudades
    case 'guardar_ciudad_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO ciudad (nom_ciudad, descripcion_ciudad) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        
        header('Location: index.php?accion=crud_ciudades');
        exit;
        break;
    
    case 'actualizar_ciudad_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE ciudad SET nom_ciudad = ?, descripcion_ciudad = ? WHERE cod_ciudad = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
        
        header('Location: index.php?accion=crud_ciudades');
        exit;
        break;
    
    case 'eliminar_ciudad_admin':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM ciudad WHERE cod_ciudad = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?accion=crud_ciudades');
        exit;
        break;
    
    // === SISTEMA DE CONTACTO ===
    case 'contacto':
        include '../src/vistas/contacto.php';
        break;
    
    // === FUNCIONALIDADES DE ACUDIENTE ===
    case 'dashboard_acudiente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/acudiente/dashboard.php';
        break;
    
    case 'ver_estudiante_acudiente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/acudiente/ver_estudiante.php';
        break;
    
    case 'eventos_estudiante_acudiente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/acudiente/eventos_estudiante.php';
        break;
    
    case 'editar_perfil_acudiente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/acudiente/editar_perfil.php';
        break;
    
    case 'actualizar_perfil_acudiente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ACU') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        try {
            $acudiente_id = $_SESSION['usuario_id'];
            $nom_user = $_POST['nom_user'] ?? '';
            $correo_user = $_POST['correo_user'] ?? '';
            $telef_user = $_POST['telef_user'] ?? null;
            $ciudad = $_POST['ciudad'] ?? null;
            
            $stmt = $pdo->prepare("UPDATE persona SET nom_user = ?, correo_user = ?, telef_user = ?, ciudad = ? WHERE id_user = ? AND tipo_persona = 'ACU'");
            $stmt->execute([$nom_user, $correo_user, $telef_user, $ciudad, $acudiente_id]);
            
            $_SESSION['usuario_nombre'] = $nom_user;
            $_SESSION['usuario_email'] = $correo_user;
            $_SESSION['success'] = 'Perfil actualizado exitosamente';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar perfil: ' . $e->getMessage();
        }
        
        header('Location: index.php?accion=editar_perfil_acudiente');
        exit;
        break;
    
    // === FUNCIONALIDADES DE DOCENTE ===
    case 'dashboard_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/dashboard.php';
        break;
    
    case 'ver_estudiantes_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/ver_estudiantes.php';
        break;
    
    case 'estudiantes_grupo_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/estudiantes_grupo.php';
        break;
    
    case 'registrar_asistencia_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/registrar_asistencia.php';
        break;
        
    case 'registrar_asistencia_qr_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar registro de asistencia por QR
            $evento_id = $_POST['cod_evento'] ?? '';
            $estudiante_id = $_POST['id_user'] ?? '';
            $docente_id = $_POST['docente_id'] ?? '';
            
            if ($evento_id && $estudiante_id && $docente_id) {
                require_once '../src/controllers/DocenteController.php';
                $docenteController = new DocenteController($pdo);
                $resultado = $docenteController->registrarAsistenciaQR($evento_id, $estudiante_id, $docente_id);
                
                // Agregar estadísticas actualizadas al resultado
                if ($resultado['success']) {
                    $stats = $docenteController->getEstadisticasEvento($evento_id);
                    $resultado['estadisticas'] = $stats;
                }
                
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                exit;
            }
        } else {
            include '../src/vistas/docente/registrar_asistencia_qr.php';
        }
        break;
        
    case 'verificar_asistencia_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $evento_id = $_POST['cod_evento'] ?? '';
            $estudiante_id = $_POST['id_user'] ?? '';
            
            if ($evento_id && $estudiante_id) {
                require_once '../src/controllers/DocenteController.php';
                $docenteController = new DocenteController($pdo);
                $asistencia = $docenteController->verificarAsistenciaExistente($evento_id, $estudiante_id);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'existe' => $asistencia ? true : false,
                    'asistencia' => $asistencia
                ]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['existe' => false, 'error' => 'Datos incompletos']);
                exit;
            }
        }
        break;
    
    case 'solicitar_evento_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/solicitar_evento.php';
        break;
    
    case 'mis_peticiones_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/mis_peticiones.php';
        break;
    
    case 'ver_historial_estudiante_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/ver_historial_estudiante.php';
        break;
    
    case 'registrar_asistencia_estudiante_docente':
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'DOC') {
            header('Location: index.php?accion=login');
            exit;
        }
        include '../src/vistas/docente/registrar_asistencia_estudiante.php';
        break;
    
    // === PÁGINA POR DEFECTO ===
    default:
        header('Location: index.php?accion=inicio');
        exit;
        break;
}
?> 