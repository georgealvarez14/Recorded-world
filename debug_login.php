<?php
/**
 * Script para debuggear el problema del login
 */

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado a la base de datos\n\n";
    
    // Verificar usuarios de prueba
    $stmt = $pdo->query("SELECT id_user, nom_user, correo_user, contrasena_user, tipo_persona FROM persona WHERE id_user IN (1000, 1001, 1002, 1003)");
    $usuarios = $stmt->fetchAll();
    
    echo "📋 Usuarios de prueba en la base de datos:\n";
    echo "┌─────┬─────────────────────┬─────────────────────┬─────────────────────┬─────────┐\n";
    echo "│ ID  │ Nombre              │ Email               │ Contraseña           │ Tipo    │\n";
    echo "├─────┼─────────────────────┼─────────────────────┼─────────────────────┼─────────┤\n";
    
    foreach ($usuarios as $usuario) {
        $password_preview = substr($usuario['contrasena_user'], 0, 20) . "...";
        printf("│ %-3s │ %-19s │ %-19s │ %-19s │ %-7s │\n", 
               $usuario['id_user'], 
               $usuario['nom_user'], 
               $usuario['correo_user'], 
               $password_preview,
               $usuario['tipo_persona']);
    }
    echo "└─────┴─────────────────────┴─────────────────────┴─────────────────────┴─────────┘\n\n";
    
    // Probar login con admin@test.com
    echo "🧪 Probando login con admin@test.com:\n";
    $email = 'admin@test.com';
    $password = '123456';
    
    $stmt = $pdo->prepare("SELECT * FROM persona WHERE correo_user = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    if ($usuario) {
        echo "✅ Usuario encontrado: " . $usuario['nom_user'] . "\n";
        echo "🔑 Contraseña en BD: " . $usuario['contrasena_user'] . "\n";
        
        // Verificar si la contraseña está encriptada
        if (password_verify($password, $usuario['contrasena_user'])) {
            echo "✅ Contraseña correcta - Login exitoso\n";
        } else {
            echo "❌ Contraseña incorrecta\n";
            
            // Verificar si la contraseña está en texto plano
            if ($usuario['contrasena_user'] === $password) {
                echo "💡 La contraseña está en texto plano, no encriptada\n";
            } else {
                echo "💡 La contraseña no coincide\n";
            }
        }
    } else {
        echo "❌ Usuario no encontrado\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 