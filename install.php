<?php
/**
 * Script de Verificación de Instalación
 * Ejecutar este archivo para verificar que todo esté configurado correctamente
 */

echo "<h1>Verificación de Instalación - Sistema de Gestión</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

$errors = [];
$warnings = [];

// 1. Verificar versión de PHP
echo "<h2>1. Verificación de PHP</h2>";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "<p class='success'>✅ PHP " . PHP_VERSION . " - Compatible</p>";
} else {
    echo "<p class='error'>❌ PHP " . PHP_VERSION . " - Se requiere PHP 7.4 o superior</p>";
    $errors[] = "Versión de PHP incompatible";
}

// 2. Verificar extensiones necesarias
echo "<h2>2. Extensiones PHP</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'json'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✅ Extensión $ext - Disponible</p>";
    } else {
        echo "<p class='error'>❌ Extensión $ext - No disponible</p>";
        $errors[] = "Extensión $ext no disponible";
    }
}

// 3. Verificar archivos y directorios
echo "<h2>3. Estructura de Archivos</h2>";
$required_files = [
    'src/config/database.php',
    'public/index.php',
    'public/.htaccess',
    'src/controllers/AuthController.php',
    'src/views/auth/login.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p class='success'>✅ $file - Existe</p>";
    } else {
        echo "<p class='error'>❌ $file - No existe</p>";
        $errors[] = "Archivo $file no encontrado";
    }
}

// 4. Verificar permisos de directorios
echo "<h2>4. Permisos de Directorios</h2>";
$directories = ['logs', 'uploads', 'public'];
foreach ($directories as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "<p class='success'>✅ Directorio $dir - Accesible</p>";
    } else {
        echo "<p class='warning'>⚠️ Directorio $dir - Verificar permisos</p>";
        $warnings[] = "Permisos del directorio $dir";
    }
}

// 5. Verificar conexión a base de datos
echo "<h2>5. Conexión a Base de Datos</h2>";
try {
    require_once 'src/config/database.php';
    global $db;
    
    if ($db && $db->getConnection()) {
        echo "<p class='success'>✅ Conexión a base de datos - Exitosa</p>";
        
        // Verificar tablas
        $tables = ['persona', 'materia', 'ciudad', 'tipo_persona'];
        foreach ($tables as $table) {
            try {
                $result = $db->query("SHOW TABLES LIKE '$table'");
                if ($result->rowCount() > 0) {
                    echo "<p class='success'>✅ Tabla $table - Existe</p>";
                } else {
                    echo "<p class='error'>❌ Tabla $table - No existe</p>";
                    $errors[] = "Tabla $table no encontrada";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error verificando tabla $table</p>";
                $errors[] = "Error en tabla $table";
            }
        }
        
    } else {
        echo "<p class='error'>❌ Conexión a base de datos - Fallida</p>";
        $errors[] = "Error de conexión a base de datos";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    $errors[] = "Error de configuración de base de datos";
}

// 6. Verificar configuración del servidor web
echo "<h2>6. Configuración del Servidor Web</h2>";
if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "<p class='success'>✅ Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
} else {
    echo "<p class='warning'>⚠️ No se pudo detectar el servidor web</p>";
}

// 7. Verificar mod_rewrite (Apache)
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p class='success'>✅ mod_rewrite - Habilitado</p>";
    } else {
        echo "<p class='warning'>⚠️ mod_rewrite - No detectado (puede estar habilitado)</p>";
        $warnings[] = "mod_rewrite no detectado";
    }
} else {
    echo "<p class='warning'>⚠️ No se pudo verificar mod_rewrite</p>";
    $warnings[] = "No se pudo verificar mod_rewrite";
}

// Resumen
echo "<h2>Resumen de la Verificación</h2>";
if (empty($errors) && empty($warnings)) {
    echo "<p class='success'><strong>🎉 ¡Instalación completada exitosamente!</strong></p>";
    echo "<p>Puedes acceder al sistema en: <a href='public/'>Sistema de Gestión</a></p>";
    echo "<p><strong>Credenciales por defecto:</strong></p>";
    echo "<ul>";
    echo "<li>Usuario: admin</li>";
    echo "<li>Contraseña: admin123</li>";
    echo "</ul>";
} else {
    if (!empty($errors)) {
        echo "<p class='error'><strong>❌ Errores encontrados:</strong></p>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li class='error'>$error</li>";
        }
        echo "</ul>";
    }
    
    if (!empty($warnings)) {
        echo "<p class='warning'><strong>⚠️ Advertencias:</strong></p>";
        echo "<ul>";
        foreach ($warnings as $warning) {
            echo "<li class='warning'>$warning</li>";
        }
        echo "</ul>";
    }
    
    echo "<p><strong>Pasos para solucionar:</strong></p>";
    echo "<ol>";
    echo "<li>Verificar la configuración de la base de datos en src/config/database.php</li>";
    echo "<li>Ejecutar el script database.sql en MySQL</li>";
    echo "<li>Verificar permisos de archivos y directorios</li>";
    echo "<li>Habilitar mod_rewrite en Apache</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><small>Script de verificación generado el: " . date('Y-m-d H:i:s') . "</small></p>";
?> 