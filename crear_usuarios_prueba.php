<?php
/**
 * Script para crear usuarios de prueba en la base de datos
 * Ejecutar una sola vez para configurar los usuarios de prueba
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'registro';
$username = 'root';
$password = '';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos\n";
    
    // Usuarios de prueba a crear
    $usuarios = [
        [
            'id' => 1000,
            'nombre' => 'Administrador Test',
            'email' => 'admin@test.com',
            'password' => '123456',
            'tipo' => 'ADM',
            'ciudad' => 'BO',
            'telefono' => 300000001
        ],
        [
            'id' => 1001,
            'nombre' => 'Docente Test',
            'email' => 'docente@test.com',
            'password' => '123456',
            'tipo' => 'DOC',
            'ciudad' => 'BO',
            'telefono' => 300000002
        ],
        [
            'id' => 1002,
            'nombre' => 'Estudiante Test',
            'email' => 'estudiante@test.com',
            'password' => '123456',
            'tipo' => 'EST',
            'ciudad' => 'BO',
            'telefono' => 300000003,
            'grado' => 61,
            'grupo' => 1
        ],
        [
            'id' => 1003,
            'nombre' => 'Acudiente Test',
            'email' => 'acudiente@test.com',
            'password' => '123456',
            'tipo' => 'ACU',
            'ciudad' => 'BO',
            'telefono' => 300000004
        ]
    ];
    
    // Verificar si los usuarios ya existen
    $stmt = $pdo->prepare("SELECT id_user FROM persona WHERE id_user IN (1000, 1001, 1002, 1003)");
    $stmt->execute();
    $existentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($existentes)) {
        echo "⚠️  Los siguientes usuarios ya existen: " . implode(', ', $existentes) . "\n";
        echo "¿Deseas eliminarlos y recrearlos? (s/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim($line) !== 's') {
            echo "❌ Operación cancelada\n";
            exit;
        }
        
        // Eliminar usuarios existentes
        $stmt = $pdo->prepare("DELETE FROM persona WHERE id_user IN (1000, 1001, 1002, 1003)");
        $stmt->execute();
        echo "🗑️  Usuarios existentes eliminados\n";
    }
    
    // Insertar usuarios de prueba
    foreach ($usuarios as $usuario) {
        $password_hash = password_hash($usuario['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO persona (
            id_user, nom_user, correo_user, contrasena_user, tipo_persona, 
            ciudad, telef_user, cod_grado, grupo, fecha_creacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $usuario['id'],
            $usuario['nombre'],
            $usuario['email'],
            $password_hash,
            $usuario['tipo'],
            $usuario['ciudad'],
            $usuario['telefono'],
            $usuario['grado'] ?? null,
            $usuario['grupo'] ?? null
        ];
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        echo "✅ Usuario creado: {$usuario['email']} (ID: {$usuario['id']})\n";
    }
    
    echo "\n🎉 ¡Usuarios de prueba creados exitosamente!\n";
    echo "\n📋 Credenciales de acceso:\n";
    echo "┌─────────────────┬─────────────────────┬────────────┐\n";
    echo "│ Rol             │ Email               │ Contraseña │\n";
    echo "├─────────────────┼─────────────────────┼────────────┤\n";
    echo "│ Administrador   │ admin@test.com      │ 123456     │\n";
    echo "│ Docente         │ docente@test.com    │ 123456     │\n";
    echo "│ Estudiante      │ estudiante@test.com │ 123456     │\n";
    echo "│ Acudiente       │ acudiente@test.com  │ 123456     │\n";
    echo "└─────────────────┴─────────────────────┴────────────┘\n";
    
    echo "\n🔗 Accede a: http://localhost/Recorded-world/public/\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 