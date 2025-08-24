<?php
/**
 * Script de prueba para verificar estudiantes en la base de datos
 */

require_once 'src/config/database.php';

try {
    global $db;
    
    echo "<h2>🧪 Test: Verificación de Estudiantes</h2>\n";
    
    // Verificar todos los estudiantes
    $estudiantes = $db->fetchAll(
        "SELECT p.id_user, p.nom_user, p.tipo_persona, p.cod_grado, p.codigo_qr, g.descripcion as grado_nombre
         FROM persona p
         LEFT JOIN grado g ON p.cod_grado = g.cod_grado
         WHERE p.tipo_persona = 'EST'
         ORDER BY p.cod_grado, p.nom_user"
    );
    
    echo "<h3>📊 Todos los Estudiantes:</h3>\n";
    echo "<table border='1' style='border-collapse: collapse;'>\n";
    echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Grado</th><th>Nombre Grado</th><th>QR</th></tr>\n";
    
    foreach ($estudiantes as $est) {
        $qr_status = $est['codigo_qr'] ? '✅ Sí' : '❌ No';
        echo "<tr>";
        echo "<td>{$est['id_user']}</td>";
        echo "<td>{$est['nom_user']}</td>";
        echo "<td>{$est['tipo_persona']}</td>";
        echo "<td>{$est['cod_grado']}</td>";
        echo "<td>{$est['grado_nombre']}</td>";
        echo "<td>{$qr_status}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    // Verificar estudiantes sin QR
    $estudiantes_sin_qr = $db->fetchAll(
        "SELECT p.id_user, p.nom_user, p.cod_grado, g.descripcion as grado_nombre
         FROM persona p
         LEFT JOIN grado g ON p.cod_grado = g.cod_grado
         WHERE p.tipo_persona = 'EST' AND p.codigo_qr IS NULL
         ORDER BY p.cod_grado, p.nom_user"
    );
    
    echo "<h3>❌ Estudiantes SIN QR:</h3>\n";
    if (empty($estudiantes_sin_qr)) {
        echo "<p>No hay estudiantes sin QR</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>ID</th><th>Nombre</th><th>Grado</th><th>Nombre Grado</th></tr>\n";
        
        foreach ($estudiantes_sin_qr as $est) {
            echo "<tr>";
            echo "<td>{$est['id_user']}</td>";
            echo "<td>{$est['nom_user']}</td>";
            echo "<td>{$est['cod_grado']}</td>";
            echo "<td>{$est['grado_nombre']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    // Verificar estudiantes por grado 6
    $estudiantes_grado6 = $db->fetchAll(
        "SELECT p.id_user, p.nom_user, p.cod_grado, p.codigo_qr, g.descripcion as grado_nombre
         FROM persona p
         LEFT JOIN grado g ON p.cod_grado = g.cod_grado
         WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE '6%'
         ORDER BY p.cod_grado, p.nom_user"
    );
    
    echo "<h3>🎓 Estudiantes Grado 6 (61, 62, 63, etc.):</h3>\n";
    if (empty($estudiantes_grado6)) {
        echo "<p>No hay estudiantes en grado 6</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>ID</th><th>Nombre</th><th>Grado</th><th>Nombre Grado</th><th>QR</th></tr>\n";
        
        foreach ($estudiantes_grado6 as $est) {
            $qr_status = $est['codigo_qr'] ? '✅ Sí' : '❌ No';
            echo "<tr>";
            echo "<td>{$est['id_user']}</td>";
            echo "<td>{$est['nom_user']}</td>";
            echo "<td>{$est['cod_grado']}</td>";
            echo "<td>{$est['grado_nombre']}</td>";
            echo "<td>{$qr_status}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    // Verificar estudiantes grado 6 sin QR
    $estudiantes_grado6_sin_qr = $db->fetchAll(
        "SELECT p.id_user, p.nom_user, p.cod_grado, g.descripcion as grado_nombre
         FROM persona p
         LEFT JOIN grado g ON p.cod_grado = g.cod_grado
         WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE '6%' AND p.codigo_qr IS NULL
         ORDER BY p.cod_grado, p.nom_user"
    );
    
    echo "<h3>❌ Estudiantes Grado 6 SIN QR:</h3>\n";
    if (empty($estudiantes_grado6_sin_qr)) {
        echo "<p>No hay estudiantes en grado 6 sin QR</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>ID</th><th>Nombre</th><th>Grado</th><th>Nombre Grado</th></tr>\n";
        
        foreach ($estudiantes_grado6_sin_qr as $est) {
            echo "<tr>";
            echo "<td>{$est['id_user']}</td>";
            echo "<td>{$est['nom_user']}</td>";
            echo "<td>{$est['cod_grado']}</td>";
            echo "<td>{$est['grado_nombre']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    echo "<h3>✅ Conclusión:</h3>\n";
    echo "<p>Total estudiantes: " . count($estudiantes) . "</p>\n";
    echo "<p>Estudiantes sin QR: " . count($estudiantes_sin_qr) . "</p>\n";
    echo "<p>Estudiantes grado 6: " . count($estudiantes_grado6) . "</p>\n";
    echo "<p>Estudiantes grado 6 sin QR: " . count($estudiantes_grado6_sin_qr) . "</p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
}
?> 