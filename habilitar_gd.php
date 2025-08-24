<?php
/**
 * Script para verificar y habilitar la extensión GD en XAMPP
 * Necesaria para generar códigos QR con la librería endroid/qr-code
 */

echo "<h1>🔧 Verificación de Extensión GD - XAMPP</h1>\n";

// Verificar si GD está habilitada
echo "<h2>📋 Estado Actual</h2>\n";
if (extension_loaded('gd')) {
    echo "✅ <strong>GD está HABILITADA</strong><br>\n";
    echo "Versión GD: " . gd_info()['GD Version'] . "<br>\n";
    echo "Formatos soportados: " . implode(', ', array_keys(array_filter(gd_info()))) . "<br>\n";
} else {
    echo "❌ <strong>GD NO está habilitada</strong><br>\n";
    echo "Esto es necesario para generar códigos QR con la librería local.<br>\n";
}

// Verificar configuración de PHP
echo "<h2>📋 Información de PHP</h2>\n";
echo "Versión PHP: " . phpversion() . "<br>\n";
echo "Archivo php.ini: " . php_ini_loaded_file() . "<br>\n";
echo "Directorio de extensiones: " . ini_get('extension_dir') . "<br>\n";

// Verificar si el archivo GD existe
$extension_dir = ini_get('extension_dir');
$gd_file = $extension_dir . '/php_gd.dll';
echo "Archivo GD: $gd_file<br>\n";
if (file_exists($gd_file)) {
    echo "✅ Archivo GD existe<br>\n";
} else {
    echo "❌ Archivo GD NO existe<br>\n";
}

// Mostrar extensiones cargadas
echo "<h2>📋 Extensiones Cargadas</h2>\n";
$loaded_extensions = get_loaded_extensions();
$gd_related = array_filter($loaded_extensions, function($ext) {
    return stripos($ext, 'gd') !== false;
});

if (!empty($gd_related)) {
    echo "Extensiones relacionadas con GD:<br>\n";
    foreach ($gd_related as $ext) {
        echo "- $ext<br>\n";
    }
} else {
    echo "No hay extensiones relacionadas con GD cargadas.<br>\n";
}

// Instrucciones para habilitar GD
echo "<h2>🔧 Instrucciones para Habilitar GD</h2>\n";

if (!extension_loaded('gd')) {
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px;'>\n";
    echo "<h3>📝 Pasos para habilitar GD en XAMPP:</h3>\n";
    echo "<ol>\n";
    echo "<li><strong>Abrir php.ini:</strong><br>\n";
    echo "   Ubicación: " . php_ini_loaded_file() . "<br>\n";
    echo "   (Normalmente en C:\\xampp\\php\\php.ini)</li>\n";
    echo "<li><strong>Buscar la línea:</strong><br>\n";
    echo "   <code>;extension=gd</code></li>\n";
    echo "<li><strong>Descomentar la línea:</strong><br>\n";
    echo "   Cambiar de: <code>;extension=gd</code><br>\n";
    echo "   A: <code>extension=gd</code></li>\n";
    echo "<li><strong>Guardar el archivo php.ini</strong></li>\n";
    echo "<li><strong>Reiniciar Apache en XAMPP:</strong><br>\n";
    echo "   - Abrir XAMPP Control Panel<br>\n";
    echo "   - Detener Apache<br>\n";
    echo "   - Iniciar Apache nuevamente</li>\n";
    echo "<li><strong>Verificar que GD esté habilitada</strong></li>\n";
    echo "</ol>\n";
    echo "</div>\n";
    
    echo "<h3>🔍 Verificación Manual</h3>\n";
    echo "<p>Después de seguir los pasos, puedes verificar si GD está habilitada de estas formas:</p>\n";
    echo "<ul>\n";
    echo "<li><strong>Crear un archivo PHP con:</strong><br>\n";
    echo "<code>&lt;?php phpinfo(); ?&gt;</code><br>\n";
    echo "Y buscar 'gd' en la página</li>\n";
    echo "<li><strong>O ejecutar este script nuevamente</strong></li>\n";
    echo "</ul>\n";
} else {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px;'>\n";
    echo "✅ <strong>¡GD está habilitada correctamente!</strong><br>\n";
    echo "Ya puedes generar códigos QR usando la librería local endroid/qr-code.\n";
    echo "</div>\n";
}

// Probar generación de QR si GD está disponible
if (extension_loaded('gd')) {
    echo "<h2>🧪 Prueba de Generación QR</h2>\n";
    
    try {
        require_once 'vendor/autoload.php';
        use Endroid\QrCode\QrCode;
        use Endroid\QrCode\Writer\PngWriter;
        
        // Crear directorio de prueba
        $test_dir = 'uploads/qr/test/';
        if (!file_exists($test_dir)) {
            mkdir($test_dir, 0777, true);
        }
        
        $test_content = "TEST:GD:WORKING:" . date('Y-m-d H:i:s');
        $test_filename = $test_dir . 'test_gd_' . date('YmdHis') . '.png';
        
        // Generar QR
        $qrCode = new QrCode($test_content);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $result->saveToFile($test_filename);
        
        if (file_exists($test_filename)) {
            echo "✅ <strong>¡Prueba exitosa!</strong><br>\n";
            echo "QR generado: $test_filename<br>\n";
            echo "Tamaño: " . filesize($test_filename) . " bytes<br>\n";
            echo "<img src='$test_filename' alt='QR de prueba' style='border: 1px solid #ccc; max-width: 200px;'><br>\n";
            echo "Contenido: <code>$test_content</code><br>\n";
        } else {
            echo "❌ Error: El archivo no se creó<br>\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error en la prueba: " . $e->getMessage() . "<br>\n";
    }
}

echo "<h2>📞 Soporte</h2>\n";
echo "<p>Si tienes problemas para habilitar GD:</p>\n";
echo "<ul>\n";
echo "<li>Verifica que estés editando el archivo php.ini correcto</li>\n";
echo "<li>Asegúrate de reiniciar Apache después de los cambios</li>\n";
echo "<li>Verifica que el archivo php_gd.dll existe en el directorio de extensiones</li>\n";
echo "<li>Si usas XAMPP, asegúrate de usar la versión correcta de PHP</li>\n";
echo "</ul>\n";

echo "<p><strong>Nota:</strong> Mientras GD no esté habilitada, el sistema usará la API externa como fallback.</p>\n";
?> 