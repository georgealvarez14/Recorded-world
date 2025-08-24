<?php
/**
 * Script de prueba específico para diagnosticar la generación masiva de QR
 */

require_once 'src/config/database.php';

try {
    global $db;
    
    echo "<h2>🔍 Diagnóstico: Generación Masiva de QR</h2>\n";
    
    // Simular la lógica del controlador paso a paso
    $cod_grado = '6'; // Como viene del formulario
    
    echo "<h3>📋 Paso 1: Verificar código de grado</h3>\n";
    echo "Código de grado recibido: '$cod_grado'<br>\n";
    echo "Longitud: " . strlen($cod_grado) . "<br>\n";
    
    if (strlen($cod_grado) == 1) {
        $cod_grado_pattern = $cod_grado . '%';
        echo "Patrón de búsqueda: '$cod_grado_pattern'<br>\n";
        
        // Obtener información del primer grado encontrado
        $grado_info = $db->fetch(
            "SELECT cod_grado, descripcion as grado_nombre FROM grado WHERE cod_grado LIKE ? LIMIT 1", 
            [$cod_grado_pattern]
        );
        
        echo "<h3>📋 Paso 2: Información del grado</h3>\n";
        if ($grado_info) {
            echo "✅ Grado encontrado: {$grado_info['cod_grado']} - {$grado_info['grado_nombre']}<br>\n";
        } else {
            echo "❌ Grado no encontrado<br>\n";
            exit;
        }
        
        // Obtener estudiantes sin QR
        $estudiantes = $db->fetchAll(
            "SELECT p.id_user, p.nom_user, p.tipo_persona, p.correo_user, p.cod_grado
             FROM persona p
             WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ? AND p.codigo_qr IS NULL
             ORDER BY p.cod_grado, p.nom_user", 
            [$cod_grado_pattern]
        );
        
        echo "<h3>📋 Paso 3: Estudiantes encontrados</h3>\n";
        echo "Total estudiantes sin QR: " . count($estudiantes) . "<br>\n";
        
        if (empty($estudiantes)) {
            echo "❌ No hay estudiantes sin QR<br>\n";
            
            // Verificar si hay estudiantes en el grado
            $total_estudiantes = $db->fetchAll(
                "SELECT COUNT(*) as total FROM persona p
                 WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ?", 
                [$cod_grado_pattern]
            );
            
            echo "Total estudiantes en el grado: {$total_estudiantes[0]['total']}<br>\n";
            
            // Verificar estudiantes con QR
            $estudiantes_con_qr = $db->fetchAll(
                "SELECT COUNT(*) as total FROM persona p
                 WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ? AND p.codigo_qr IS NOT NULL", 
                [$cod_grado_pattern]
            );
            
            echo "Estudiantes con QR: {$estudiantes_con_qr[0]['total']}<br>\n";
        } else {
            echo "<h4>📊 Lista de estudiantes:</h4>\n";
            echo "<table border='1' style='border-collapse: collapse;'>\n";
            echo "<tr><th>ID</th><th>Nombre</th><th>Grado</th><th>Tipo</th></tr>\n";
            
            foreach ($estudiantes as $est) {
                echo "<tr>";
                echo "<td>{$est['id_user']}</td>";
                echo "<td>{$est['nom_user']}</td>";
                echo "<td>{$est['cod_grado']}</td>";
                echo "<td>{$est['tipo_persona']}</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
            
            echo "<h3>📋 Paso 4: Simular generación de QR</h3>\n";
            
            // Cargar el controlador QR
            require_once 'src/controllers/QRController.php';
            $controller = new QRController();
            
            $generados = 0;
            $errores = [];
            
            foreach ($estudiantes as $estudiante) {
                try {
                    echo "Generando QR para {$estudiante['nom_user']} (ID: {$estudiante['id_user']})... ";
                    $qr_path = $controller->generarQRPersona($estudiante['id_user']);
                    echo "✅ Generado: $qr_path<br>\n";
                    $generados++;
                } catch (Exception $e) {
                    echo "❌ Error: " . $e->getMessage() . "<br>\n";
                    $errores[] = "Error generando QR para {$estudiante['nom_user']}: " . $e->getMessage();
                }
            }
            
            echo "<h3>📋 Paso 5: Resultado</h3>\n";
            echo "QR generados: $generados<br>\n";
            echo "Errores: " . count($errores) . "<br>\n";
            
            if (!empty($errores)) {
                echo "<h4>❌ Errores encontrados:</h4>\n";
                foreach ($errores as $error) {
                    echo "- $error<br>\n";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general: " . $e->getMessage() . "</p>\n";
    echo "<p>Stack trace:</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}
?> 