<?php
/**
 * CONFIGURACIÓN SIMPLIFICADA DEL SISTEMA
 * 
 * Este archivo explica las partes básicas del sistema para estudiantes
 */

// ========================================
// 1. CONFIGURACIÓN DE BASE DE DATOS
// ========================================

// Datos de conexión a MySQL
$host = 'localhost';        // Servidor de base de datos
$dbname = 'registro';       // Nombre de la base de datos
$username = 'root';         // Usuario (por defecto en XAMPP)
$password = '';             // Contraseña (vacía por defecto en XAMPP)

// Crear conexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// ========================================
// 2. CONFIGURACIÓN DE SESIONES
// ========================================

// Iniciar sesión para mantener al usuario logueado
session_start();

// ========================================
// 3. TIPOS DE USUARIOS (ROLES)
// ========================================

$tipos_usuario = [
    'ADM' => 'Administrador',    // Puede hacer todo
    'DOC' => 'Docente',          // Puede gestionar eventos y asistencia
    'EST' => 'Estudiante',       // Puede ver eventos e inscribirse
    'ACU' => 'Acudiente'         // Puede ver información limitada
];

// ========================================
// 4. FUNCIONES ÚTILES
// ========================================

/**
 * Verificar si el usuario está logueado
 */
function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Verificar si el usuario es administrador
 */
function esAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'ADM';
}

/**
 * Verificar si el usuario es estudiante
 */
function esEstudiante() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'EST';
}

/**
 * Verificar si el usuario es docente
 */
function esDocente() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'DOC';
}

/**
 * Redirigir si no está logueado
 */
function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: index.php?accion=login');
        exit;
    }
}

/**
 * Redirigir si no es administrador
 */
function requiereAdmin() {
    requiereLogin();
    if (!esAdmin()) {
        header('Location: index.php?accion=inicio');
        exit;
    }
}

// ========================================
// 5. CONFIGURACIÓN DE MENSAJES
// ========================================

/**
 * Mostrar mensaje de éxito
 */
function mostrarExito($mensaje) {
    $_SESSION['success'] = $mensaje;
}

/**
 * Mostrar mensaje de error
 */
function mostrarError($mensaje) {
    $_SESSION['error'] = $mensaje;
}

/**
 * Obtener mensajes para mostrar
 */
function obtenerMensajes() {
    $mensajes = [];
    
    if (isset($_SESSION['success'])) {
        $mensajes['success'] = $_SESSION['success'];
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error'])) {
        $mensajes['error'] = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    
    return $mensajes;
}

// ========================================
// 6. CONFIGURACIÓN DE ARCHIVOS
// ========================================

// Directorio para subir archivos
$upload_dir = '../uploads/';

// Directorio para códigos QR
$qr_dir = $upload_dir . 'qr/';

// Crear directorios si no existen
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!is_dir($qr_dir)) {
    mkdir($qr_dir, 0777, true);
}

// ========================================
// 7. CONFIGURACIÓN DE FECHAS
// ========================================

// Zona horaria
date_default_timezone_set('America/Bogota');

// Formato de fecha para mostrar
function formatearFecha($fecha) {
    return date('d/m/Y', strtotime($fecha));
}

// Formato de hora para mostrar
function formatearHora($hora) {
    return date('H:i', strtotime($hora));
}

// ========================================
// 8. CONFIGURACIÓN DE VALIDACIONES
// ========================================

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validar que no esté vacío
 */
function validarRequerido($valor) {
    return !empty(trim($valor));
}

/**
 * Limpiar texto de entrada
 */
function limpiarTexto($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

// ========================================
// 9. CONFIGURACIÓN DE PAGINACIÓN
// ========================================

$registros_por_pagina = 10; // Número de registros por página

/**
 * Calcular offset para paginación
 */
function calcularOffset($pagina) {
    global $registros_por_pagina;
    return ($pagina - 1) * $registros_por_pagina;
}

// ========================================
// 10. CONFIGURACIÓN DE BÚSQUEDA
// ========================================

/**
 * Preparar término de búsqueda para SQL
 */
function prepararBusqueda($termino) {
    return '%' . $termino . '%';
}

// ========================================
// EXPLICACIÓN PARA ESTUDIANTES
// ========================================

/*
¿QUÉ HACE CADA PARTE?

1. BASE DE DATOS: Conecta el sistema con MySQL
2. SESIONES: Mantiene al usuario logueado
3. ROLES: Define qué puede hacer cada tipo de usuario
4. FUNCIONES: Código reutilizable para validaciones
5. MENSAJES: Sistema para mostrar alertas al usuario
6. ARCHIVOS: Configuración para subir archivos y QR
7. FECHAS: Formateo de fechas y horas
8. VALIDACIONES: Verificar que los datos sean correctos
9. PAGINACIÓN: Dividir listas largas en páginas
10. BÚSQUEDA: Preparar términos para buscar en la BD

¿CÓMO USARLO?

1. Incluye este archivo en tus páginas:
   include 'config_simple.php';

2. Usa las funciones disponibles:
   if (esAdmin()) { ... }
   mostrarExito("Operación exitosa");
   validarEmail($email);

3. La conexión $pdo ya está lista para usar en consultas SQL

¿POR QUÉ ES ÚTIL?

- Centraliza la configuración
- Evita repetir código
- Hace el sistema más seguro
- Facilita el mantenimiento
- Es fácil de entender y modificar
*/

?> 