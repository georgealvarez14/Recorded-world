<?php
/**
 * Test de Email con PHPMailer
 * 
 * Este archivo prueba el envío de emails usando PHPMailer
 * Ejecutar desde la línea de comandos: php test_email_phpmailer.php
 */

echo "🧪 Test de Email con PHPMailer\n";
echo "==============================\n\n";

// 1. Verificar PHPMailer
echo "1. Verificando PHPMailer...\n";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "   ✅ Autoload de Composer encontrado\n";
} else {
    echo "   ❌ Autoload de Composer no encontrado\n";
    exit;
}

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "   ✅ PHPMailer disponible\n";
} else {
    echo "   ❌ PHPMailer no disponible\n";
    exit;
}

// 2. Cargar configuración
echo "2. Cargando configuración...\n";
require_once 'src/config/email_config.php';

$status = getEmailStatus();
echo "   ✅ Email habilitado: " . ($status['enabled'] ? 'Sí' : 'No') . "\n";
echo "   ✅ Modo simulación: " . ($status['simulation'] ? 'Sí' : 'No') . "\n";

if ($status['simulation']) {
    echo "   ⚠️ El sistema está en modo simulación\n";
    echo "   💡 Cambia EMAIL_SIMULATION a false para emails reales\n";
}

// 3. Probar envío con PHPMailer
echo "3. Probando envío con PHPMailer...\n";

try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = SMTP_AUTH;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    
    // Configuración de debug (solo para pruebas)
    $mail->SMTPDebug = 2; // Mostrar información detallada
    
    // Destinatarios
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(ADMIN_EMAIL);
    $mail->addReplyTo('noreply@institucion.edu.co');
    
    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "🧪 Test PHPMailer - Sistema de Eventos";
    $mail->Body = "
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
            <h2>🧪 Test PHPMailer Exitoso</h2>
        </div>
        <div class='content'>
            <p><strong>Fecha:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Servidor:</strong> " . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost') . "</p>
            <p><strong>PHP Version:</strong> " . phpversion() . "</p>
            <p><strong>PHPMailer:</strong> Configurado correctamente</p>
            <p>Este email confirma que PHPMailer está funcionando correctamente con Gmail.</p>
        </div>
        <div class='footer'>
            <p>Sistema de Gestión de Eventos - Test PHPMailer</p>
        </div>
    </body>
    </html>";
    
    $mail->AltBody = "Test PHPMailer - Sistema de Eventos\n\nFecha: " . date('d/m/Y H:i:s') . "\nPHP Version: " . phpversion() . "\n\nEste email confirma que PHPMailer está funcionando correctamente.";
    
    // Enviar email
    $result = $mail->send();
    
    if ($result) {
        echo "   ✅ Email enviado exitosamente a: " . ADMIN_EMAIL . "\n";
        echo "   📧 Revisa tu bandeja de entrada\n";
    } else {
        echo "   ❌ Error enviando email\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    echo "   💡 Verifica:\n";
    echo "      - Contraseña de aplicación (no contraseña normal)\n";
    echo "      - Verificación en dos pasos activada\n";
    echo "      - Credenciales correctas\n";
}

// 4. Mostrar configuración
echo "\n4. Configuración actual:\n";
echo "   📧 SMTP Host: " . SMTP_HOST . "\n";
echo "   📧 SMTP Port: " . SMTP_PORT . "\n";
echo "   📧 SMTP Secure: " . SMTP_SECURE . "\n";
echo "   📧 SMTP Username: " . SMTP_USERNAME . "\n";
echo "   📧 Admin Email: " . ADMIN_EMAIL . "\n";

// 5. Verificar contraseña
echo "\n5. Verificación de contraseña:\n";
if (SMTP_PASSWORD === 'TU_CONTRASEÑA_DE_APLICACION') {
    echo "   ❌ Contraseña no configurada\n";
    echo "   💡 Genera una contraseña de aplicación en Google\n";
} else {
    echo "   ✅ Contraseña configurada\n";
}

echo "\n✅ Test completado\n";
echo "📧 Si recibiste el email, PHPMailer está funcionando\n";
echo "📧 Si no recibiste el email, revisa la configuración SMTP\n";
?> 