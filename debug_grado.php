<?php
/**
 * Script de diagnóstico para verificar valores de grado
 */

require_once 'src/config/database.php';

try {
    global $db;
    
    echo "<h2>🔍 Diagnóstico: Valores de Grado</h2>\n";
    
    // Mostrar todos los grados disponibles
    $grados = $db->fetchAll("SELECT cod_grado, descripcion FROM grado ORDER BY cod_grado");
    
    echo "<h3>📋 Grados Disponibles en la Base de Datos:</h3>\n";
    echo "<table border='1' style='border-collapse: collapse;'>\n";
    echo "<tr><th>Código</th><th>Descripción</th></tr>\n";
    
    foreach ($grados as $grado) {
        echo "<tr>";
        echo "<td>{$grado['cod_grado']}</td>";
        echo "<td>{$grado['descripcion']}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Mostrar datos POST si existen
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h3>📋 Datos POST Recibidos:</h3>\n";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        
        if (isset($_POST['cod_grado'])) {
            $cod_grado = $_POST['cod_grado'];
            echo "<h3>🔍 Verificación del código de grado: '$cod_grado'</h3>\n";
            
            $grado_existe = $db->fetch(
                "SELECT cod_grado, descripcion FROM grado WHERE cod_grado = ?", 
                [$cod_grado]
            );
            
            if ($grado_existe) {
                echo "✅ El código de grado '$cod_grado' EXISTE en la base de datos<br>\n";
                echo "Descripción: {$grado_existe['descripcion']}<br>\n";
            } else {
                echo "❌ El código de grado '$cod_grado' NO EXISTE en la base de datos<br>\n";
            }
        }
    }
    
    // Mostrar formulario de prueba
    echo "<h3>🧪 Formulario de Prueba:</h3>\n";
    echo "<form method='POST'>\n";
    echo "<label for='cod_grado'>Código de Grado:</label><br>\n";
    echo "<select name='cod_grado' id='cod_grado'>\n";
    echo "<option value=''>Selecciona un grado</option>\n";
    
    foreach ($grados as $grado) {
        echo "<option value='{$grado['cod_grado']}'>{$grado['cod_grado']} - {$grado['descripcion']}</option>\n";
    }
    
    echo "</select><br><br>\n";
    echo "<input type='submit' value='Probar'>\n";
    echo "</form>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
}
?> 