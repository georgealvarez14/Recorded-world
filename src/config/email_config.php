<?php

/**
 * Configuración del Sistema de Email
 * 
 * Este archivo contiene la configuración para el envío de emails
 * desde el sistema de gestión de eventos.
 */

// Configuración del servidor SMTP
define('SMTP_HOST', 'smtp.gmail.com');           // Servidor SMTP
define('SMTP_PORT', 587);                        // Puerto SMTP
define('SMTP_SECURE', 'tls');                    // Tipo de seguridad (tls/ssl)
define('SMTP_AUTH', true);                       // Requiere autenticación

// Credenciales del email
define('SMTP_USERNAME', 'recordedworld941@gmail.com');   // Email del remitente
define('SMTP_PASSWORD', 'nelb pwtg jhgi bzxv');  // Reemplaza con tu contraseña de aplicación real
define('SMTP_FROM_EMAIL', 'recordedworld941@gmail.com');  // Email de origen
define('SMTP_FROM_NAME', 'Sistema de Eventos');  // Nombre del remitente

// Configuración de destinatarios
define('ADMIN_EMAIL', 'recordedworld941@gmail.com');     // Email del administrador
define('SUPPORT_EMAIL', 'soporte@institucion.edu.co');    // Email de soporte

// Configuración de la aplicación
define('EMAIL_ENABLED', true);                   // Habilitar/deshabilitar emails
define('EMAIL_SIMULATION', false);               // Email real (no simulación)
define('EMAIL_LOG_ENABLED', true);               // Habilitar logs de email

// Configuración de límites
define('MAX_EMAILS_PER_HOUR', 100);              // Máximo emails por hora
define('MAX_EMAILS_PER_DAY', 1000);              // Máximo emails por día

/**
 * Función para verificar si el email está habilitado
 */
function isEmailEnabled() {
    return EMAIL_ENABLED === true;
}

/**
 * Función para verificar si está en modo simulación
 */
function isEmailSimulation() {
    return EMAIL_SIMULATION === true;
}

/**
 * Función para obtener la configuración SMTP
 */
function getSMTPConfig() {
    return [
        'host' => SMTP_HOST,
        'port' => SMTP_PORT,
        'secure' => SMTP_SECURE,
        'auth' => SMTP_AUTH,
        'username' => SMTP_USERNAME,
        'password' => SMTP_PASSWORD,
        'from_email' => SMTP_FROM_EMAIL,
        'from_name' => SMTP_FROM_NAME
    ];
}

/**
 * Función para validar configuración de email
 */
function validateEmailConfig() {
    $errors = [];
    
    if (empty(SMTP_USERNAME) || SMTP_USERNAME === 'tu-email@gmail.com') {
        $errors[] = 'SMTP_USERNAME no está configurado';
    }
    
    if (empty(SMTP_PASSWORD) || SMTP_PASSWORD === 'tu-password') {
        $errors[] = 'SMTP_PASSWORD no está configurado';
    }
    
    if (empty(ADMIN_EMAIL) || ADMIN_EMAIL === 'admin@institucion.edu.co') {
        $errors[] = 'ADMIN_EMAIL no está configurado';
    }
    
    return $errors;
}

/**
 * Función para obtener el estado del sistema de email
 */
function getEmailStatus() {
    $config = getSMTPConfig();
    $errors = validateEmailConfig();
    
    return [
        'enabled' => isEmailEnabled(),
        'simulation' => isEmailSimulation(),
        'configured' => empty($errors),
        'errors' => $errors,
        'smtp_host' => $config['host'],
        'admin_email' => ADMIN_EMAIL
    ];
} 