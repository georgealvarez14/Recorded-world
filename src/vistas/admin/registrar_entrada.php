<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$qr = $data['qr'];
$jornada = $data['jornada'];

// Aquí deberías buscar el estudiante por el QR y registrar la entrada en la BD
// Ejemplo básico:
$nombre = "Juan Pérez"; // Buscar en BD por $qr
$grado = "6°A";         // Buscar en BD por $qr
$hora = date('H:i:s');

echo json_encode([
    'success' => true,
    'nombre' => $nombre,
    'grado' => $grado,
    'hora' => $hora
]);
?>