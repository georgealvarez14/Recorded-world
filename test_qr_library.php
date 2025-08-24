<?php
/**
 * Script de prueba para verificar la librería QR local
 */

require_once 'src/config/database.php';
require_once 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

try {
    echo "<h2>🧪 Test: Librería QR Local</h2>\n";
    
    // Verificar que la librería se carga correctamente
    echo "<h3>📋 Paso 1: Verificar librería</h3>\n";
    
    if (class_exists('Endroid\QrCode\QrCode')) {
        echo "✅ Clase QrCode encontrada<br>\n";
    } else {
        echo "❌ Clase QrCode NO encontrada<br>\n";
        exit;
    }
    
    if (class_exists('Endroid\QrCode\Writer\PngWriter')) {
        echo "✅ Clase PngWriter encontrada<br>\n";
    } else {
        echo "❌ Clase PngWriter NO encontrada<br>\n";
        exit;
    }
    
    // Crear directorio de prueba
    echo "<h3>📋 Paso 2: Crear directorio de prueba</h3>\n";
    $test_dir = 'uploads/qr/test/';
    if (!file_exists($test_dir)) {
        mkdir($test_dir, 0777, true);
        echo "✅ Directorio de prueba creado: $test_dir<br>\n";
    } else {
        echo "✅ Directorio de prueba ya existe: $test_dir<br>\n";
    }
    
    // Generar QR de prueba
    echo "<h3>📋 Paso 3: Generar QR de prueba</h3>\n";
    
    $test_content = "TEST:QR:LIBRARY:WORKING";
    $test_filename = $test_dir . 'test_qr_' . date('YmdHis') . '.png';
    
    try {
        // Crear QR usando la librería
        $qrCode = new QrCode($test_content);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        // Guardar la imagen QR
        $result->saveToFile($test_filename);
        
        if (file_exists($test_filename)) {
            echo "✅ QR generado exitosamente: $test_filename<br>\n";
            echo "Tamaño del archivo: " . filesize($test_filename) . " bytes<br>\n";
            
            // Mostrar la imagen
            echo "<h3>📋 Paso 4: Mostrar QR generado</h3>\n";
            echo "<img src='$test_filename' alt='QR de prueba' style='border: 1px solid #ccc;'><br>\n";
            echo "<p>Contenido del QR: <code>$test_content</code></p>\n";
            
        } else {
            echo "❌ Error: El archivo no se creó<br>\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error generando QR: " . $e->getMessage() . "<br>\n";
    }
    
    // Probar con contenido JSON (como en el sistema real)
    echo "<h3>📋 Paso 5: Probar con contenido JSON</h3>\n";
    
    $json_content = json_encode([
        'tipo' => 'persona',
        'id' => '999',
        'nom' => 'Test User',
        'tip' => 'EST',
        'em' => 'test@test.com',
        'gr' => 'Sexto 1',
        'ts' => time()
    ]);
    
    $json_filename = $test_dir . 'test_json_qr_' . date('YmdHis') . '.png';
    
    try {
        $qrCode2 = new QrCode($json_content);
        $qrCode2->setSize(300);
        $qrCode2->setMargin(10);
        
        $writer2 = new PngWriter();
        $result2 = $writer2->write($qrCode2);
        
        $result2->saveToFile($json_filename);
        
        if (file_exists($json_filename)) {
            echo "✅ QR JSON generado exitosamente: $json_filename<br>\n";
            echo "Tamaño del archivo: " . filesize($json_filename) . " bytes<br>\n";
            
            echo "<img src='$json_filename' alt='QR JSON de prueba' style='border: 1px solid #ccc;'><br>\n";
            echo "<p>Contenido JSON: <code>" . htmlspecialchars($json_content) . "</code></p>\n";
            
        } else {
            echo "❌ Error: El archivo JSON no se creó<br>\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error generando QR JSON: " . $e->getMessage() . "<br>\n";
    }
    
    echo "<h3>✅ Conclusión</h3>\n";
    echo "<p>Si puedes ver las imágenes QR arriba, la librería está funcionando correctamente.</p>\n";
    echo "<p>Si no ves las imágenes, hay un problema con la librería o los permisos de archivo.</p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error general: " . $e->getMessage() . "</p>\n";
}
?> 