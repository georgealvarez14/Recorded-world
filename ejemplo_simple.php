<?php
/**
 * EJEMPLO SIMPLIFICADO DEL SISTEMA
 * 
 * Este archivo muestra cómo funciona el sistema de manera básica
 * Para estudiantes que quieren entender el código
 */

// Incluir configuración
include 'config_simple.php';

// Obtener mensajes
$mensajes = obtenerMensajes();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Eventos - Ejemplo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .code {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏫 Sistema de Gestión de Eventos</h1>
            <p>Ejemplo simplificado para estudiantes</p>
        </div>

        <!-- Mostrar mensajes -->
        <?php if (!empty($mensajes)): ?>
            <?php foreach ($mensajes as $tipo => $mensaje): ?>
                <div class="section <?php echo $tipo; ?>">
                    <strong><?php echo ucfirst($tipo); ?>:</strong> <?php echo $mensaje; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Información del usuario -->
        <div class="section">
            <h3>👤 Información del Usuario</h3>
            <?php if (estaLogueado()): ?>
                <p><strong>ID:</strong> <?php echo $_SESSION['usuario_id']; ?></p>
                <p><strong>Nombre:</strong> <?php echo $_SESSION['usuario_nombre']; ?></p>
                <p><strong>Tipo:</strong> <?php echo $tipos_usuario[$_SESSION['usuario_tipo']] ?? $_SESSION['usuario_tipo']; ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['usuario_email']; ?></p>
                
                <a href="index.php?accion=logout" class="button">🚪 Cerrar Sesión</a>
            <?php else: ?>
                <p>No hay usuario logueado</p>
                <a href="index.php?accion=login" class="button">🔑 Iniciar Sesión</a>
            <?php endif; ?>
        </div>

        <!-- Funcionalidades disponibles -->
        <div class="section">
            <h3>🎯 Funcionalidades Disponibles</h3>
            
            <?php if (estaLogueado()): ?>
                <?php if (esAdmin()): ?>
                    <h4>👨‍💼 Funciones de Administrador:</h4>
                    <ul>
                        <li><a href="index.php?accion=crud_personas" class="button">👥 Gestionar Personas</a></li>
                        <li><a href="index.php?accion=crud_eventos" class="button">📅 Gestionar Eventos</a></li>
                        <li><a href="index.php?accion=crud_materias" class="button">📚 Gestionar Materias</a></li>
                        <li><a href="index.php?accion=generar_qr" class="button">📱 Generar QR</a></li>
                        <li><a href="index.php?accion=control_entrada" class="button">🚪 Control de Entrada</a></li>
                    </ul>
                <?php elseif (esDocente()): ?>
                    <h4>👨‍🏫 Funciones de Docente:</h4>
                    <ul>
                        <li><a href="index.php?accion=eventos" class="button">📅 Ver Eventos</a></li>
                        <li><a href="index.php?accion=registrar_asistencia" class="button">✅ Registrar Asistencia</a></li>
                    </ul>
                <?php elseif (esEstudiante()): ?>
                    <h4>👨‍🎓 Funciones de Estudiante:</h4>
                    <ul>
                        <li><a href="index.php?accion=eventos_disponibles" class="button">📅 Eventos Disponibles</a></li>
                        <li><a href="index.php?accion=mis_eventos" class="button">📋 Mis Eventos</a></li>
                        <li><a href="index.php?accion=registrar_asistencia" class="button">✅ Registrar Asistencia</a></li>
                        <li><a href="index.php?accion=mi_perfil" class="button">👤 Mi Perfil</a></li>
                    </ul>
                <?php endif; ?>
            <?php else: ?>
                <p>Inicia sesión para ver las funcionalidades disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Ejemplo de consulta a base de datos -->
        <div class="section">
            <h3>📊 Ejemplo de Consulta a Base de Datos</h3>
            
            <?php
            // Ejemplo: contar total de personas
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM persona");
                $total_personas = $stmt->fetch()['total'];
                
                // Ejemplo: contar eventos
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM evento");
                $total_eventos = $stmt->fetch()['total'];
                
                // Ejemplo: obtener últimos 5 eventos
                $stmt = $pdo->query("SELECT cod_evento, nom_evento, fecha_inicio FROM evento ORDER BY fecha_inicio DESC LIMIT 5");
                $ultimos_eventos = $stmt->fetchAll();
            } catch (Exception $e) {
                $error_db = $e->getMessage();
            }
            ?>
            
            <div class="code">
                <strong>Estadísticas del Sistema:</strong><br>
                📊 Total de personas: <?php echo $total_personas ?? 'Error'; ?><br>
                📅 Total de eventos: <?php echo $total_eventos ?? 'Error'; ?>
            </div>
            
            <?php if (isset($ultimos_eventos) && !empty($ultimos_eventos)): ?>
                <h4>📅 Últimos 5 Eventos:</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_eventos as $evento): ?>
                            <tr>
                                <td><?php echo $evento['cod_evento']; ?></td>
                                <td><?php echo $evento['nom_evento']; ?></td>
                                <td><?php echo formatearFecha($evento['fecha_inicio']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <?php if (isset($error_db)): ?>
                <div class="section error">
                    <strong>Error en base de datos:</strong> <?php echo $error_db; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Explicación del código -->
        <div class="section">
            <h3>💡 Explicación del Código</h3>
            
            <h4>1. Conexión a Base de Datos:</h4>
            <div class="code">
// Crear conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=registro", "root", "");
            </div>
            
            <h4>2. Verificar Sesión:</h4>
            <div class="code">
// Verificar si está logueado
if (estaLogueado()) {
    echo "Usuario logueado";
}
            </div>
            
            <h4>3. Consulta SQL:</h4>
            <div class="code">
// Consulta preparada (segura)
$stmt = $pdo->prepare("SELECT * FROM persona WHERE id_user = ?");
$stmt->execute([$id]);
$persona = $stmt->fetch();
            </div>
            
            <h4>4. Mostrar Mensajes:</h4>
            <div class="code">
// Mostrar mensaje de éxito
mostrarExito("Operación completada");

// Mostrar mensaje de error  
mostrarError("Algo salió mal");
            </div>
        </div>

        <!-- Navegación -->
        <div class="section">
            <h3>🧭 Navegación</h3>
            <a href="index.php" class="button">🏠 Inicio</a>
            <a href="index.php?accion=login" class="button">🔑 Login</a>
            <a href="index.php?accion=contacto" class="button">📞 Contacto</a>
        </div>

        <!-- Información del sistema -->
        <div class="section">
            <h3>ℹ️ Información del Sistema</h3>
            <p><strong>Versión:</strong> Simplificada para estudiantes</p>
            <p><strong>Lenguaje:</strong> PHP</p>
            <p><strong>Base de Datos:</strong> MySQL</p>
            <p><strong>Servidor:</strong> XAMPP (Apache)</p>
            <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
    </div>
</body>
</html> 