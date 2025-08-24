<?php
/**
 * Test de Configuración de Email
 * 
 * Este archivo prueba la configuración del sistema de email
 * Ejecutar desde la línea de comandos: php test_email.php
 */

echo "🧪 Test de Configuración de Email\n";
echo "================================\n\n";

// 1. Verificar configuración
echo "1. Verificando configuración...\n";
require_once 'src/config/email_config.php';

$status = getEmailStatus();
echo "   ✅ Email habilitado: " . ($status['enabled'] ? 'Sí' : 'No') . "\n";
echo "   ✅ Modo simulación: " . ($status['simulation'] ? 'Sí' : 'No') . "\n";
echo "   ✅ Configuración válida: " . ($status['configured'] ? 'Sí' : 'No') . "\n";

if (!empty($status['errors'])) {
    echo "   ❌ Errores encontrados:\n";
    foreach ($status['errors'] as $error) {
        echo "      - $error\n";
    }
    echo "\n";
}

// 2. Verificar función mail()
echo "2. Verificando función mail()...\n";
if (function_exists('mail')) {
    echo "   ✅ Función mail() disponible\n";
} else {
    echo "   ❌ Función mail() no disponible\n";
}

// 3. Verificar PHPMailer
echo "3. Verificando PHPMailer...\n";
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "   ✅ PHPMailer disponible\n";
} else {
    echo "   ⚠️ PHPMailer no disponible (opcional)\n";
}

// 4. Probar envío de email
echo "4. Probando envío de email...\n";

$to = ADMIN_EMAIL;
$subject = "🧪 Test de Email - Sistema de Eventos";
$htmlMessage = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #667eea; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #eee; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class='header'>
        <h2>🧪 Test de Email Exitoso</h2>
    </div>
    <div class='content'>
        <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
        <p><strong>Servidor:</strong> " . $_SERVER['SERVER_NAME'] ?? 'localhost' . "</p>
        <p><strong>PHP Version:</strong> " . phpversion() . "</p>
        <p>Este email confirma que la configuración de email está funcionando correctamente.</p>
    </div>
    <div class='footer'>
        <p>Sistema de Gestión de Eventos - Test de Configuración</p>
    </div>
</body>
</html>";

$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: Sistema de Eventos <' . SMTP_FROM_EMAIL . '>',
    'Reply-To: noreply@institucion.edu.co',
    'X-Mailer: PHP/' . phpversion()
);

try {
    $sent = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));
    
    if ($sent) {
        echo "   ✅ Email enviado correctamente a: $to\n";
        echo "   📧 Revisa tu bandeja de entrada\n";
    } else {
        echo "   ❌ Error enviando email\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Excepción: " . $e->getMessage() . "\n";
}

// 5. Verificar directorio de logs
echo "5. Verificando directorio de logs...\n";
$logDir = 'logs/';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
    echo "   ✅ Directorio de logs creado\n";
} else {
    echo "   ✅ Directorio de logs existe\n";
}

// 6. Verificar directorio de simulación
echo "6. Verificando directorio de simulación...\n";
$simulationDir = 'logs/email_simulation/';
if (!file_exists($simulationDir)) {
    mkdir($simulationDir, 0777, true);
    echo "   ✅ Directorio de simulación creado\n";
} else {
    echo "   ✅ Directorio de simulación existe\n";
}

// 7. Mostrar configuración actual
echo "\n7. Configuración actual:\n";
echo "   📧 SMTP Host: " . SMTP_HOST . "\n";
echo "   📧 SMTP Port: " . SMTP_PORT . "\n";
echo "   📧 SMTP Username: " . SMTP_USERNAME . "\n";
echo "   📧 Admin Email: " . ADMIN_EMAIL . "\n";
echo "   📧 From Email: " . SMTP_FROM_EMAIL . "\n";

// 8. Recomendaciones
echo "\n8. Recomendaciones:\n";

if ($status['simulation']) {
    echo "   💡 El sistema está en modo simulación\n";
    echo "   💡 Para emails reales, cambia EMAIL_SIMULATION a false\n";
}

if (!empty($status['errors'])) {
    echo "   ⚠️ Corrige los errores de configuración antes de usar\n";
}

if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "   💡 Instala PHPMailer para mejor control: composer require phpmailer/phpmailer\n";
}

echo "\n✅ Test completado\n";
echo "📧 Si recibiste el email, la configuración está funcionando\n";
echo "📧 Si no recibiste el email, revisa la configuración SMTP\n";
?> 