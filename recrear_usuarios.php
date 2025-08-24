<?php
/**
 * Script para recrear usuarios de prueba con contraseñas que funcionen
 */

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos\n";
    
    // Eliminar usuarios existentes
    $stmt = $pdo->prepare("DELETE FROM persona WHERE id_user IN (1000, 1001, 1002, 1003)");
    $stmt->execute();
    echo "🗑️  Usuarios existentes eliminados\n";
    
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
        
        // Verificar que la contraseña funciona
        if (password_verify($usuario['password'], $password_hash)) {
            echo "   ✅ Contraseña verificada correctamente\n";
        } else {
            echo "   ❌ Error en verificación de contraseña\n";
        }
    }
    
    echo "\n🎉 ¡Usuarios de prueba recreados exitosamente!\n";
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