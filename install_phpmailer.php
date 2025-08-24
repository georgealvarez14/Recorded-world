<?php
/**
 * Instalador Automático de PHPMailer
 * 
 * Este script descarga e instala PHPMailer automáticamente
 * Ejecutar desde la línea de comandos: php install_phpmailer.php
 */

echo "📧 Instalador Automático de PHPMailer\n";
echo "====================================\n\n";

// Verificar si ya está instalado
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer ya está instalado\n";
    exit;
}

// Verificar si Composer está disponible
echo "1. Verificando Composer...\n";
$composerAvailable = false;
if (file_exists('composer.json')) {
    echo "   ✅ composer.json encontrado\n";
    $composerAvailable = true;
} else {
    echo "   ⚠️ composer.json no encontrado\n";
}

// Intentar instalar con Composer
if ($composerAvailable) {
    echo "2. Intentando instalar con Composer...\n";
    
    $output = [];
    $returnCode = 0;
    
    // Ejecutar comando composer
    exec('composer require phpmailer/phpmailer 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ PHPMailer instalado exitosamente con Composer\n";
        echo "   📁 Ubicación: vendor/phpmailer/phpmailer/\n";
        exit;
    } else {
        echo "   ❌ Error instalando con Composer:\n";
        foreach ($output as $line) {
            echo "      $line\n";
        }
    }
}

// Instalación manual
echo "3. Instalación manual de PHPMailer...\n";

// Crear directorio vendor si no existe
if (!file_exists('vendor')) {
    mkdir('vendor', 0777, true);
    echo "   ✅ Directorio vendor creado\n";
}

// Crear directorio phpmailer
$phpmailerDir = 'vendor/phpmailer/phpmailer/src/';
if (!file_exists($phpmailerDir)) {
    mkdir($phpmailerDir, 0777, true);
    echo "   ✅ Directorio PHPMailer creado\n";
}

// URLs de los archivos necesarios
$files = [
    'PHPMailer.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php',
    'SMTP.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php',
    'Exception.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php'
];

$success = true;

foreach ($files as $filename => $url) {
    echo "   📥 Descargando $filename...\n";
    
    $content = file_get_contents($url);
    if ($content !== false) {
        $filepath = $phpmailerDir . $filename;
        if (file_put_contents($filepath, $content) !== false) {
            echo "   ✅ $filename descargado exitosamente\n";
        } else {
            echo "   ❌ Error guardando $filename\n";
            $success = false;
        }
    } else {
        echo "   ❌ Error descargando $filename\n";
        $success = false;
    }
}

if ($success) {
    echo "\n✅ PHPMailer instalado manualmente\n";
    echo "📁 Ubicación: $phpmailerDir\n";
    
    // Crear archivo de autoload simple
    $autoloadContent = '<?php
// Autoload simple para PHPMailer
require_once __DIR__ . "/vendor/phpmailer/phpmailer/src/Exception.php";
require_once __DIR__ . "/vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once __DIR__ . "/vendor/phpmailer/phpmailer/src/SMTP.php";
?>';
    
    file_put_contents('vendor/autoload.php', $autoloadContent);
    echo "📁 Autoload creado: vendor/autoload.php\n";
    
} else {
    echo "\n❌ Error en la instalación manual\n";
    echo "💡 Intenta instalar manualmente desde: https://github.com/PHPMailer/PHPMailer\n";
}

echo "\n📋 Próximos pasos:\n";
echo "1. Ejecuta: php test_email.php\n";
echo "2. Verifica que PHPMailer esté funcionando\n";
echo "3. Configura las credenciales en src/config/email_config.php\n";
?> 