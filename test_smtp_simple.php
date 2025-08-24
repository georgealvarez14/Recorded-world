<?php
/**
 * Test Simple de SMTP
 * 
 * Este archivo diagnostica problemas de conexión SMTP
 */

echo "🔍 Test Simple de SMTP\n";
echo "=====================\n\n";

// 1. Cargar configuración
require_once 'src/config/email_config.php';

echo "1. Configuración actual:\n";
echo "   📧 Host: " . SMTP_HOST . "\n";
echo "   📧 Puerto: " . SMTP_PORT . "\n";
echo "   📧 Usuario: " . SMTP_USERNAME . "\n";
echo "   📧 Contraseña: " . (strlen(SMTP_PASSWORD) > 10 ? 'Configurada' : 'No configurada') . "\n";
echo "   📧 Seguridad: " . SMTP_SECURE . "\n\n";

// 2. Verificar si la contraseña está configurada
if (SMTP_PASSWORD === 'TU_CONTRASEÑA_DE_APLICACION') {
    echo "❌ PROBLEMA: Contraseña no configurada\n";
    echo "💡 Necesitas generar una contraseña de aplicación en Google:\n";
    echo "   1. Ve a https://myaccount.google.com/\n";
    echo "   2. Seguridad → Verificación en dos pasos (activar)\n";
    echo "   3. Seguridad → Contraseñas de aplicación\n";
    echo "   4. Selecciona 'Otra' → Nombre: 'Sistema de Eventos'\n";
    echo "   5. Copia la contraseña de 16 caracteres\n";
    echo "   6. Actualiza SMTP_PASSWORD en email_config.php\n\n";
    exit;
}

// 3. Test de conexión básica
echo "2. Test de conexión básica...\n";

$host = SMTP_HOST;
$port = SMTP_PORT;

echo "   🔌 Conectando a $host:$port...\n";

$connection = @fsockopen($host, $port, $errno, $errstr, 10);

if ($connection) {
    echo "   ✅ Conexión exitosa\n";
    fclose($connection);
} else {
    echo "   ❌ Error de conexión: $errstr ($errno)\n";
    echo "   💡 Verifica:\n";
    echo "      - Conexión a internet\n";
    echo "      - Firewall del servidor\n";
    echo "      - Puerto $port abierto\n\n";
}

// 4. Verificar PHPMailer
echo "3. Verificando PHPMailer...\n";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "   ✅ PHPMailer disponible\n";
    } else {
        echo "   ❌ PHPMailer no disponible\n";
    }
} else {
    echo "   ❌ Autoload no encontrado\n";
}

echo "\n📋 Próximos pasos:\n";
echo "1. Si la contraseña no está configurada, genera una contraseña de aplicación\n";
echo "2. Si hay problemas de conexión, verifica tu red/firewall\n";
echo "3. Ejecuta: php test_email_phpmailer.php\n";
?> 