<?php
// Script para crear usuarios de prueba con contraseñas en texto plano
// Versión simplificada para exposición académica

echo "<h2>👥 Creando Usuarios de Prueba</h2>";
echo "<p>Contraseñas en texto plano (sin encriptar)</p>";

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=registro;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado a la base de datos<br>";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// Usuarios de prueba con contraseñas en texto plano
$usuarios_prueba = [
    ['id' => 1000, 'nombre' => 'Administrador Test', 'email' => 'admin@test.com', 'password' => '123456', 'tipo' => 'ADM'],
    ['id' => 1001, 'nombre' => 'Docente Test', 'email' => 'docente@test.com', 'password' => '123456', 'tipo' => 'DOC'],
    ['id' => 1002, 'nombre' => 'Estudiante Test', 'email' => 'estudiante@test.com', 'password' => '123456', 'tipo' => 'EST'],
    ['id' => 1003, 'nombre' => 'Acudiente Test', 'email' => 'acudiente@test.com', 'password' => '123456', 'tipo' => 'ACU'],
];

// Eliminar usuarios existentes si existen
$ids_a_eliminar = implode(',', array_column($usuarios_prueba, 'id'));
$stmt = $pdo->prepare("DELETE FROM persona WHERE id_user IN ($ids_a_eliminar)");
$stmt->execute();
echo "🗑️ Usuarios existentes eliminados<br>";

// Insertar nuevos usuarios con contraseñas en texto plano
$stmt = $pdo->prepare("INSERT INTO persona (id_user, nom_user, correo_user, contrasena_user, tipo_persona) VALUES (?, ?, ?, ?, ?)");
foreach ($usuarios_prueba as $usuario) {
    $stmt->execute([$usuario['id'], $usuario['nombre'], $usuario['email'], $usuario['password'], $usuario['tipo']]);
    echo "✅ Usuario creado: " . $usuario['email'] . " (ID: " . $usuario['id'] . ")<br>";
}

echo "<br>🎉 ¡Usuarios de prueba creados exitosamente!<br><br>";

echo "<h3>📋 Credenciales de acceso:</h3>";
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background-color: #f0f0f0;'><th>Rol</th><th>Email</th><th>Contraseña</th></tr>";
foreach ($usuarios_prueba as $usuario) {
    echo "<tr>";
    echo "<td style='padding: 5px;'>" . $usuario['tipo'] . "</td>";
    echo "<td style='padding: 5px;'>" . $usuario['email'] . "</td>";
    echo "<td style='padding: 5px;'>" . $usuario['password'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><strong>🔗 Accede a:</strong> <a href='public/index.php?accion=login'>http://localhost/Recorded-world/public/</a><br>";
echo "<br><strong>⚠️ Nota:</strong> Las contraseñas están en texto plano para facilitar la exposición académica.<br>";
?> 