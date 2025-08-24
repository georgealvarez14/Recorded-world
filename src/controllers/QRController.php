<?php
/**
 * Controlador para Gestión de Códigos QR
 * Genera y maneja códigos QR para eventos y personas
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRController {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    /**
     * Genera un código QR para un evento
     */
    public function generarQREvento($cod_evento) {
        try {
            // Obtener información del evento
            $evento = $this->db->fetch(
                "SELECT cod_evento, nom_evento, fecha_inicio, ubicacion FROM evento WHERE cod_evento = ?", 
                [$cod_evento]
            );
            
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            
            // Crear directorio de QR si no existe
            $qr_dir = 'uploads/qr/eventos/';
            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0777, true);
            }
            
            // Generar contenido del QR
            $qr_content = json_encode([
                'tipo' => 'evento',
                'cod' => $evento['cod_evento'],
                'nom' => $evento['nom_evento'],
                'fec' => $evento['fecha_inicio'],
                'ubi' => $evento['ubicacion'],
                'ts' => time()
            ]);
            
            // Generar nombre del archivo
            $qr_filename = 'evento_' . $cod_evento . '_' . date('YmdHis') . '.png';
            $qr_path = $qr_dir . $qr_filename;
            
            // Generar QR usando la librería local
            try {
                $qrCode = new QrCode($qr_content);
                $qrCode->setSize(300);
                $qrCode->setMargin(10);
                
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                
                // Guardar la imagen QR
                $result->saveToFile($qr_path);
                
                // Verificar que el archivo se creó correctamente
                if (!file_exists($qr_path)) {
                    throw new Exception('No se pudo crear el archivo QR');
                }
                
            } catch (Exception $e) {
                error_log("Error generando QR con librería local: " . $e->getMessage());
                
                // Segundo intento: usar API gratuita
                if ($this->generarQRConAPI($qr_content, $qr_path)) {
                    error_log("QR generado exitosamente usando API");
                } else {
                    // Fallback final: crear un archivo de texto temporal
                    $qr_content_text = "QR EVENTO TEMPORAL\n";
                    $qr_content_text .= "Código: {$evento['cod_evento']}\n";
                    $qr_content_text .= "Nombre: {$evento['nom_evento']}\n";
                    $qr_content_text .= "Fecha: {$evento['fecha_inicio']}\n";
                    $qr_content_text .= "Ubicación: {$evento['ubicacion']}\n";
                    $qr_content_text .= "Fecha Generación: " . date('Y-m-d H:i:s') . "\n";
                    
                    // Cambiar extensión a .txt para archivos temporales
                    $qr_filename = 'evento_' . $cod_evento . '_' . date('YmdHis') . '.txt';
                    $qr_path = $qr_dir . $qr_filename;
                    
                    // Guardar contenido como archivo de texto
                    file_put_contents($qr_path, $qr_content_text);
                }
            }
            
            // Actualizar la base de datos
            $this->db->execute(
                "UPDATE evento SET qr = ? WHERE cod_evento = ?", 
                [$qr_path, $cod_evento]
            );
            
            return $qr_path;
            
        } catch (Exception $e) {
            error_log("Error generando QR para evento: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Genera un código QR para una persona
     */
    public function generarQRPersona($id_user) {
        try {
            // Obtener información de la persona con grado
            $persona = $this->db->fetch(
                "SELECT p.id_user, p.nom_user, p.tipo_persona, p.correo_user, p.cod_grado, g.descripcion as grado_nombre
                 FROM persona p
                 LEFT JOIN grado g ON p.cod_grado = g.cod_grado
                 WHERE p.id_user = ?", 
                [$id_user]
            );
            
            if (!$persona) {
                throw new Exception('Persona no encontrada');
            }
            
            // Crear estructura de directorios por grado
            $qr_base_dir = 'uploads/qr/personas/';
            if (!file_exists($qr_base_dir)) {
                mkdir($qr_base_dir, 0777, true);
            }
            
            // Determinar el directorio según el tipo de persona y grado
            if ($persona['tipo_persona'] === 'EST' && !empty($persona['cod_grado'])) {
                // Para estudiantes, organizar por grado
                $grado_dir = $this->getGradoDirectory($persona['cod_grado'], $persona['grado_nombre']);
                $qr_dir = $qr_base_dir . $grado_dir . '/';
            } else {
                // Para otros tipos (profesores, admin, acudientes)
                $qr_dir = $qr_base_dir . 'otros/';
            }
            
            // Crear el directorio específico si no existe
            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0777, true);
            }
            
            // Generar contenido del QR
            $qr_content = json_encode([
                'tipo' => 'persona',
                'id' => $persona['id_user'],
                'nom' => $persona['nom_user'],
                'tip' => $persona['tipo_persona'],
                'em' => $persona['correo_user'],
                'gr' => $persona['grado_nombre'] ?? null,
                'ts' => time()
            ]);
            
            // Generar nombre del archivo
            $qr_filename = 'persona_' . $id_user . '_' . date('YmdHis') . '.png';
            $qr_path = $qr_dir . $qr_filename;
            
            // Generar QR usando la librería local
            try {
                $qrCode = new QrCode($qr_content);
                $qrCode->setSize(300);
                $qrCode->setMargin(10);
                
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                
                // Guardar la imagen QR
                $result->saveToFile($qr_path);
                
                // Verificar que el archivo se creó correctamente
                if (!file_exists($qr_path)) {
                    throw new Exception('No se pudo crear el archivo QR');
                }
                
            } catch (Exception $e) {
                error_log("Error generando QR con librería local: " . $e->getMessage());
                
                // Segundo intento: usar API gratuita
                if ($this->generarQRConAPI($qr_content, $qr_path)) {
                    error_log("QR generado exitosamente usando API");
                } else {
                    // Fallback final: crear un archivo de texto temporal
                    $qr_content_text = "QR TEMPORAL\n";
                    $qr_content_text .= "ID: {$persona['id_user']}\n";
                    $qr_content_text .= "Nombre: {$persona['nom_user']}\n";
                    $qr_content_text .= "Tipo: {$persona['tipo_persona']}\n";
                    $qr_content_text .= "Email: {$persona['correo_user']}\n";
                    $qr_content_text .= "Grado: " . ($persona['grado_nombre'] ?? 'N/A') . "\n";
                    $qr_content_text .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
                    
                    // Cambiar extensión a .txt para archivos temporales
                    $qr_filename = 'persona_' . $id_user . '_' . date('YmdHis') . '.txt';
                    $qr_path = $qr_dir . $qr_filename;
                    
                    // Guardar contenido como archivo de texto
                    file_put_contents($qr_path, $qr_content_text);
                }
            }
            
            // Actualizar la base de datos
            $this->db->execute(
                "UPDATE persona SET codigo_qr = ? WHERE id_user = ?", 
                [$qr_path, $id_user]
            );
            
            return $qr_path;
            
        } catch (Exception $e) {
            error_log("Error generando QR para persona: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Genera un QR usando una API gratuita como fallback
     */
    private function generarQRConAPI($contenido, $ruta_archivo) {
        try {
            // Usar QR Server API (gratuita)
            $api_url = 'https://api.qrserver.com/v1/create-qr-code/';
            $params = [
                'size' => '300x300',
                'data' => $contenido,
                'format' => 'png'
            ];
            
            $url = $api_url . '?' . http_build_query($params);
            
            // Configurar contexto para la petición HTTP
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            // Descargar la imagen QR
            $qr_image = file_get_contents($url, false, $context);
            
            if ($qr_image === false) {
                throw new Exception('No se pudo descargar el QR desde la API');
            }
            
            // Guardar la imagen
            if (file_put_contents($ruta_archivo, $qr_image) === false) {
                throw new Exception('No se pudo guardar el archivo QR');
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error generando QR con API: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lee un código QR y retorna la información
     */
    public function leerQR($qr_content) {
        try {
            $data = json_decode($qr_content, true);
            
            if (!$data) {
                throw new Exception('Código QR inválido');
            }
            
            if ($data['tipo'] === 'evento') {
                return $this->procesarQREvento($data);
            } elseif ($data['tipo'] === 'persona') {
                return $this->procesarQRPersona($data);
            } else {
                throw new Exception('Tipo de QR no reconocido');
            }
            
        } catch (Exception $e) {
            error_log("Error leyendo QR: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Procesa un QR de evento
     */
    private function procesarQREvento($data) {
        $evento = $this->db->fetch(
            "SELECT e.*, m.descripcion as materia_nombre, tj.descripcion as jornada_nombre
             FROM evento e
             LEFT JOIN materias m ON e.materia = m.cod_categoria
             LEFT JOIN tipo_jornada tj ON e.tipo_jornada = tj.cod_jornada
             WHERE e.cod_evento = ?", 
            [$data['cod_evento']]
        );
        
        if (!$evento) {
            throw new Exception('Evento no encontrado en la base de datos');
        }
        
        return [
            'tipo' => 'evento',
            'evento' => $evento,
            'qr_data' => $data
        ];
    }
    
    /**
     * Procesa un QR de persona
     */
    private function procesarQRPersona($data) {
        $persona = $this->db->fetch(
            "SELECT p.*, g.descripcion as grado_nombre, c.descripcion as ciudad_nombre
             FROM persona p
             LEFT JOIN grado g ON p.cod_grado = g.cod_grado
             LEFT JOIN ciudad c ON p.ciudad = c.cod_ciudad
             WHERE p.id_user = ?", 
            [$data['id_user']]
        );
        
        if (!$persona) {
            throw new Exception('Persona no encontrada en la base de datos');
        }
        
        return [
            'tipo' => 'persona',
            'persona' => $persona,
            'qr_data' => $data
        ];
    }
    
    /**
     * Registra asistencia usando QR
     */
    public function registrarAsistenciaQR($cod_evento, $id_user) {
        try {
            // Verificar que el evento existe
            $evento = $this->db->fetch(
                "SELECT cod_evento, nom_evento, fecha_inicio FROM evento WHERE cod_evento = ?", 
                [$cod_evento]
            );
            
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            
            // Verificar que la persona existe
            $persona = $this->db->fetch(
                "SELECT id_user, nom_user FROM persona WHERE id_user = ?", 
                [$id_user]
            );
            
            if (!$persona) {
                throw new Exception('Persona no encontrada');
            }
            
            // Verificar que la persona está inscrita en el evento
            $participante = $this->db->fetch(
                "SELECT * FROM participante WHERE cod_evento = ? AND id_user = ?", 
                [$cod_evento, $id_user]
            );
            
            if (!$participante) {
                throw new Exception('La persona no está inscrita en este evento');
            }
            
            // Verificar si ya registró asistencia
            $asistencia_existente = $this->db->fetch(
                "SELECT * FROM registro_participante WHERE cod_evento = ? AND id_user = ?", 
                [$cod_evento, $id_user]
            );
            
            if ($asistencia_existente) {
                throw new Exception('Ya se registró la asistencia para esta persona');
            }
            
            // Registrar asistencia
            $this->db->execute(
                "INSERT INTO registro_participante (cod_evento, id_user, fecha_llegada) VALUES (?, ?, NOW())", 
                [$cod_evento, $id_user]
            );
            
            return [
                'success' => true,
                'message' => 'Asistencia registrada correctamente',
                'persona' => $persona['nom_user'],
                'evento' => $evento['nom_evento'],
                'fecha' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            error_log("Error registrando asistencia QR: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene todos los QR generados
     */
    public function getQREventos() {
        try {
            return $this->db->fetchAll(
                "SELECT cod_evento, nom_evento, qr, fecha_inicio FROM evento WHERE qr IS NOT NULL ORDER BY fecha_inicio DESC"
            );
        } catch (Exception $e) {
            error_log("Error obteniendo QR de eventos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene el nombre del directorio para un grado específico
     */
    public function getGradoDirectory($cod_grado, $grado_nombre) {
        // Mapear códigos de grado a nombres de directorio
        $grado_map = [
            '6' => '6to',
            '7' => '7mo',
            '8' => '8vo',
            '9' => '9no',
            '10' => '10mo',
            '11' => '11mo'
        ];
        
        // Si tenemos un mapeo directo, usarlo
        if (isset($grado_map[$cod_grado])) {
            return $grado_map[$cod_grado];
        }
        
        // Si no, usar el nombre del grado y limpiarlo
        $clean_name = preg_replace('/[^a-zA-Z0-9]/', '', $grado_nombre);
        return strtolower($clean_name);
    }
    
    /**
     * Obtiene la estructura de directorios de QR organizados por grado
     */
    public function getEstructuraQREstudiantes() {
        try {
            $estructura = [];
            $qr_base_dir = 'uploads/qr/personas/';
            
            if (!file_exists($qr_base_dir)) {
                return $estructura;
            }
            
            // Obtener todos los grados con estudiantes que tienen QR
            $grados = $this->db->fetchAll(
                "SELECT DISTINCT p.cod_grado, g.descripcion as grado_nombre, COUNT(p.id_user) as total_estudiantes
                 FROM persona p
                 LEFT JOIN grado g ON p.cod_grado = g.cod_grado
                 WHERE p.tipo_persona = 'EST' AND p.codigo_qr IS NOT NULL
                 GROUP BY p.cod_grado, g.descripcion
                 ORDER BY p.cod_grado"
            );
            
            foreach ($grados as $grado) {
                $grado_dir = $this->getGradoDirectory($grado['cod_grado'], $grado['grado_nombre']);
                $dir_path = $qr_base_dir . $grado_dir . '/';
                
                if (file_exists($dir_path)) {
                    $estructura[] = [
                        'grado_codigo' => $grado['cod_grado'],
                        'grado_nombre' => $grado['grado_nombre'],
                        'directorio' => $grado_dir,
                        'ruta' => $dir_path,
                        'total_estudiantes' => $grado['total_estudiantes'],
                        'archivos' => $this->contarArchivosQR($dir_path)
                    ];
                }
            }
            
            return $estructura;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estructura QR estudiantes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cuenta los archivos QR en un directorio
     */
    private function contarArchivosQR($dir_path) {
        if (!file_exists($dir_path)) {
            return 0;
        }
        
        $archivos = glob($dir_path . '*.png');
        return count($archivos);
    }
    
    /**
     * Genera códigos QR masivamente para un grado específico
     */
    public function generarQRMasivoGrado($cod_grado) {
        try {
            // Convertir el código de grado simple a formato de base de datos
            // Si viene "6", buscar grados que empiecen con "6" (61, 62, 63, etc.)
            if (strlen($cod_grado) == 1) {
                $cod_grado_pattern = $cod_grado . '%';
                
                // Obtener información del primer grado encontrado para el reporte
                $grado_info = $this->db->fetch(
                    "SELECT cod_grado, descripcion as grado_nombre FROM grado WHERE cod_grado LIKE ? LIMIT 1", 
                    [$cod_grado_pattern]
                );
                
                if (!$grado_info) {
                    throw new Exception('Grado no encontrado');
                }
                
                // Obtener todos los estudiantes de todos los grados que empiecen con ese número
                $estudiantes = $this->db->fetchAll(
                    "SELECT p.id_user, p.nom_user, p.tipo_persona, p.correo_user, p.cod_grado
                     FROM persona p
                     WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ? AND p.codigo_qr IS NULL
                     ORDER BY p.cod_grado, p.nom_user", 
                    [$cod_grado_pattern]
                );
                
                if (empty($estudiantes)) {
                    // Verificar si hay estudiantes en el grado pero ya tienen QR
                    $total_estudiantes = $this->db->fetchAll(
                        "SELECT COUNT(*) as total FROM persona p
                         WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ?", 
                        [$cod_grado_pattern]
                    );
                    
                    if ($total_estudiantes[0]['total'] > 0) {
                        throw new Exception("No hay estudiantes sin QR en este grado. Todos los estudiantes ya tienen QR generados.");
                    } else {
                        throw new Exception("No hay estudiantes registrados en este grado");
                    }
                }
            } else {
                // Si viene un código completo (61, 62, etc.), buscar exactamente
                $grado_info = $this->db->fetch(
                    "SELECT cod_grado, descripcion as grado_nombre FROM grado WHERE cod_grado = ?", 
                    [$cod_grado]
                );
                
                if (!$grado_info) {
                    throw new Exception('Grado no encontrado');
                }
                
                // Obtener todos los estudiantes del grado específico que no tienen QR
                $estudiantes = $this->db->fetchAll(
                    "SELECT p.id_user, p.nom_user, p.tipo_persona, p.correo_user, p.cod_grado
                     FROM persona p
                     WHERE p.tipo_persona = 'EST' AND p.cod_grado = ? AND p.codigo_qr IS NULL
                     ORDER BY p.nom_user", 
                    [$cod_grado]
                );
                
                if (empty($estudiantes)) {
                    // Verificar si hay estudiantes en el grado pero ya tienen QR
                    $total_estudiantes = $this->db->fetchAll(
                        "SELECT COUNT(*) as total FROM persona p
                         WHERE p.tipo_persona = 'EST' AND p.cod_grado = ?", 
                        [$cod_grado]
                    );
                    
                    if ($total_estudiantes[0]['total'] > 0) {
                        throw new Exception("No hay estudiantes sin QR en este grado. Todos los estudiantes ya tienen QR generados.");
                    } else {
                        throw new Exception("No hay estudiantes registrados en este grado");
                    }
                }
            }
            
            $generados = 0;
            $errores = [];
            
            foreach ($estudiantes as $estudiante) {
                try {
                    $this->generarQRPersona($estudiante['id_user']);
                    $generados++;
                } catch (Exception $e) {
                    $errores[] = "Error generando QR para {$estudiante['nom_user']}: " . $e->getMessage();
                }
            }
            
            // Determinar el nombre del grado para el reporte
            if (strlen($cod_grado) == 1) {
                $grado_nombre = "Grado " . $cod_grado . " (todos los grupos)";
            } else {
                $grado_nombre = $grado_info['grado_nombre'];
            }
            
            return [
                'success' => true,
                'grado' => $grado_nombre,
                'total_estudiantes' => count($estudiantes),
                'generados' => $generados,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error generando QR masivo para grado: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Genera códigos QR masivamente para todos los estudiantes
     */
    public function generarQRMasivoTodos() {
        try {
            // Obtener todos los estudiantes que no tienen QR
            $estudiantes = $this->db->fetchAll(
                "SELECT p.id_user, p.nom_user, p.tipo_persona, p.correo_user, p.cod_grado, g.descripcion as grado_nombre
                 FROM persona p
                 LEFT JOIN grado g ON p.cod_grado = g.cod_grado
                 WHERE p.tipo_persona = 'EST' AND p.codigo_qr IS NULL
                 ORDER BY p.cod_grado, p.nom_user"
            );
            
            if (empty($estudiantes)) {
                throw new Exception('No hay estudiantes sin QR');
            }
            
            $generados = 0;
            $errores = [];
            $por_grado = [];
            
            foreach ($estudiantes as $estudiante) {
                try {
                    $this->generarQRPersona($estudiante['id_user']);
                    $generados++;
                    
                    // Contar por grado
                    $grado = $estudiante['grado_nombre'] ?? 'Sin Grado';
                    if (!isset($por_grado[$grado])) {
                        $por_grado[$grado] = 0;
                    }
                    $por_grado[$grado]++;
                    
                } catch (Exception $e) {
                    $errores[] = "Error generando QR para {$estudiante['nom_user']}: " . $e->getMessage();
                }
            }
            
            return [
                'success' => true,
                'total_estudiantes' => count($estudiantes),
                'generados' => $generados,
                'por_grado' => $por_grado,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error generando QR masivo para todos: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Genera códigos QR masivamente para todos los eventos
     */
    public function generarQRMasivoEventos() {
        try {
            // Obtener todos los eventos que no tienen QR
            $eventos = $this->db->fetchAll(
                "SELECT cod_evento, nom_evento, fecha_inicio, ubicacion
                 FROM evento 
                 WHERE qr IS NULL
                 ORDER BY fecha_inicio DESC"
            );
            
            if (empty($eventos)) {
                throw new Exception('No hay eventos sin QR');
            }
            
            $generados = 0;
            $errores = [];
            
            foreach ($eventos as $evento) {
                try {
                    $this->generarQREvento($evento['cod_evento']);
                    $generados++;
                } catch (Exception $e) {
                    $errores[] = "Error generando QR para evento {$evento['nom_evento']}: " . $e->getMessage();
                }
            }
            
            return [
                'success' => true,
                'total_eventos' => count($eventos),
                'generados' => $generados,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error generando QR masivo para eventos: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getQRPersonas() {
        try {
            return $this->db->fetchAll(
                "SELECT id_user, nom_user, tipo_persona, codigo_qr FROM persona WHERE codigo_qr IS NOT NULL ORDER BY nom_user"
            );
        } catch (Exception $e) {
            error_log("Error obteniendo QR de personas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Elimina un QR específico de una persona
     */
    public function eliminarQRPersona($id_user) {
        try {
            // Obtener información de la persona
            $persona = $this->db->fetch(
                "SELECT id_user, nom_user, codigo_qr FROM persona WHERE id_user = ?", 
                [$id_user]
            );
            
            if (!$persona) {
                throw new Exception('Persona no encontrada');
            }
            
            if (!$persona['codigo_qr']) {
                throw new Exception('La persona no tiene QR generado');
            }
            
            // Eliminar archivo físico
            if (file_exists($persona['codigo_qr'])) {
                unlink($persona['codigo_qr']);
            }
            
            // Actualizar base de datos
            $this->db->execute(
                "UPDATE persona SET codigo_qr = NULL WHERE id_user = ?", 
                [$id_user]
            );
            
            return [
                'success' => true,
                'message' => "QR eliminado correctamente para {$persona['nom_user']}"
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR de persona: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina un QR específico de un evento
     */
    public function eliminarQREvento($cod_evento) {
        try {
            // Obtener información del evento
            $evento = $this->db->fetch(
                "SELECT cod_evento, nom_evento, qr FROM evento WHERE cod_evento = ?", 
                [$cod_evento]
            );
            
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            
            if (!$evento['qr']) {
                throw new Exception('El evento no tiene QR generado');
            }
            
            // Eliminar archivo físico
            if (file_exists($evento['qr'])) {
                unlink($evento['qr']);
            }
            
            // Actualizar base de datos
            $this->db->execute(
                "UPDATE evento SET qr = NULL WHERE cod_evento = ?", 
                [$cod_evento]
            );
            
            return [
                'success' => true,
                'message' => "QR eliminado correctamente para el evento {$evento['nom_evento']}"
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR de evento: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina todos los QR de un grado específico
     */
    public function eliminarQRMasivoGrado($cod_grado) {
        try {
            // Si viene un solo dígito, buscar todos los grados que empiecen con ese número
            if (strlen($cod_grado) == 1) {
                $cod_grado_pattern = $cod_grado . '%';
                
                // Obtener información del primer grado encontrado para el reporte
                $grado_info = $this->db->fetch(
                    "SELECT cod_grado, descripcion as grado_nombre FROM grado WHERE cod_grado LIKE ? LIMIT 1", 
                    [$cod_grado_pattern]
                );
                
                if (!$grado_info) {
                    throw new Exception('Grado no encontrado');
                }
                
                // Obtener todos los estudiantes del grado con QR
                $estudiantes = $this->db->fetchAll(
                    "SELECT p.id_user, p.nom_user, p.codigo_qr, p.cod_grado
                     FROM persona p
                     WHERE p.tipo_persona = 'EST' AND p.cod_grado LIKE ? AND p.codigo_qr IS NOT NULL
                     ORDER BY p.cod_grado, p.nom_user", 
                    [$cod_grado_pattern]
                );
            } else {
                // Búsqueda exacta
                $grado_info = $this->db->fetch(
                    "SELECT cod_grado, descripcion as grado_nombre FROM grado WHERE cod_grado = ?", 
                    [$cod_grado]
                );
                
                if (!$grado_info) {
                    throw new Exception('Grado no encontrado');
                }
                
                $estudiantes = $this->db->fetchAll(
                    "SELECT p.id_user, p.nom_user, p.codigo_qr, p.cod_grado
                     FROM persona p
                     WHERE p.tipo_persona = 'EST' AND p.cod_grado = ? AND p.codigo_qr IS NOT NULL
                     ORDER BY p.nom_user", 
                    [$cod_grado]
                );
            }
            
            if (empty($estudiantes)) {
                return [
                    'success' => true,
                    'message' => "No hay QR para eliminar en el grado {$grado_info['grado_nombre']}",
                    'eliminados' => 0
                ];
            }
            
            $eliminados = 0;
            $errores = [];
            
            foreach ($estudiantes as $estudiante) {
                try {
                    // Eliminar archivo físico
                    if (file_exists($estudiante['codigo_qr'])) {
                        unlink($estudiante['codigo_qr']);
                    }
                    
                    // Actualizar base de datos
                    $this->db->execute(
                        "UPDATE persona SET codigo_qr = NULL WHERE id_user = ?", 
                        [$estudiante['id_user']]
                    );
                    
                    $eliminados++;
                    
                } catch (Exception $e) {
                    $errores[] = "Error eliminando QR de {$estudiante['nom_user']}: " . $e->getMessage();
                }
            }
            
            $mensaje = "Se eliminaron {$eliminados} QR del grado {$grado_info['grado_nombre']}";
            if (!empty($errores)) {
                $mensaje .= ". Algunos QR no se pudieron eliminar. Revisa los logs.";
            }
            
            return [
                'success' => true,
                'message' => $mensaje,
                'eliminados' => $eliminados,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR masivo por grado: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina todos los QR de personas (estudiantes, profesores, etc.)
     */
    public function eliminarQRMasivoPersonas() {
        try {
            // Obtener todas las personas con QR
            $personas = $this->db->fetchAll(
                "SELECT id_user, nom_user, codigo_qr FROM persona WHERE codigo_qr IS NOT NULL ORDER BY nom_user"
            );
            
            if (empty($personas)) {
                return [
                    'success' => true,
                    'message' => 'No hay QR de personas para eliminar',
                    'eliminados' => 0
                ];
            }
            
            $eliminados = 0;
            $errores = [];
            
            foreach ($personas as $persona) {
                try {
                    // Eliminar archivo físico
                    if (file_exists($persona['codigo_qr'])) {
                        unlink($persona['codigo_qr']);
                    }
                    
                    // Actualizar base de datos
                    $this->db->execute(
                        "UPDATE persona SET codigo_qr = NULL WHERE id_user = ?", 
                        [$persona['id_user']]
                    );
                    
                    $eliminados++;
                    
                } catch (Exception $e) {
                    $errores[] = "Error eliminando QR de {$persona['nom_user']}: " . $e->getMessage();
                }
            }
            
            $mensaje = "Se eliminaron {$eliminados} QR de personas";
            if (!empty($errores)) {
                $mensaje .= ". Algunos QR no se pudieron eliminar. Revisa los logs.";
            }
            
            return [
                'success' => true,
                'message' => $mensaje,
                'eliminados' => $eliminados,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR masivo de personas: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina todos los QR de eventos
     */
    public function eliminarQRMasivoEventos() {
        try {
            // Obtener todos los eventos con QR
            $eventos = $this->db->fetchAll(
                "SELECT cod_evento, nom_evento, qr FROM evento WHERE qr IS NOT NULL ORDER BY nom_evento"
            );
            
            if (empty($eventos)) {
                return [
                    'success' => true,
                    'message' => 'No hay QR de eventos para eliminar',
                    'eliminados' => 0
                ];
            }
            
            $eliminados = 0;
            $errores = [];
            
            foreach ($eventos as $evento) {
                try {
                    // Eliminar archivo físico
                    if (file_exists($evento['qr'])) {
                        unlink($evento['qr']);
                    }
                    
                    // Actualizar base de datos
                    $this->db->execute(
                        "UPDATE evento SET qr = NULL WHERE cod_evento = ?", 
                        [$evento['cod_evento']]
                    );
                    
                    $eliminados++;
                    
                } catch (Exception $e) {
                    $errores[] = "Error eliminando QR del evento {$evento['nom_evento']}: " . $e->getMessage();
                }
            }
            
            $mensaje = "Se eliminaron {$eliminados} QR de eventos";
            if (!empty($errores)) {
                $mensaje .= ". Algunos QR no se pudieron eliminar. Revisa los logs.";
            }
            
            return [
                'success' => true,
                'message' => $mensaje,
                'eliminados' => $eliminados,
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando QR masivo de eventos: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina todos los QR del sistema (personas + eventos)
     */
    public function eliminarQRMasivoTodos() {
        try {
            $resultado_personas = $this->eliminarQRMasivoPersonas();
            $resultado_eventos = $this->eliminarQRMasivoEventos();
            
            $total_eliminados = $resultado_personas['eliminados'] + $resultado_eventos['eliminados'];
            
            return [
                'success' => true,
                'message' => "Se eliminaron {$total_eliminados} QR en total ({$resultado_personas['eliminados']} personas, {$resultado_eventos['eliminados']} eventos)",
                'eliminados' => $total_eliminados,
                'personas' => $resultado_personas['eliminados'],
                'eventos' => $resultado_eventos['eliminados']
            ];
            
        } catch (Exception $e) {
            error_log("Error eliminando todos los QR: " . $e->getMessage());
            throw $e;
        }
    }
}

// Si se llama directamente este archivo
if (basename($_SERVER['PHP_SELF']) == 'QRController.php') {
    $controller = new QRController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        try {
            switch ($action) {
                case 'generar_evento':
                    $cod_evento = $_POST['cod_evento'] ?? '';
                    $qr_path = $controller->generarQREvento($cod_evento);
                    echo json_encode(['success' => true, 'qr_path' => $qr_path]);
                    break;
                    
                case 'generar_persona':
                    $id_user = $_POST['id_user'] ?? '';
                    $qr_path = $controller->generarQRPersona($id_user);
                    echo json_encode(['success' => true, 'qr_path' => $qr_path]);
                    break;
                    
                case 'registrar_asistencia':
                    $cod_evento = $_POST['cod_evento'] ?? '';
                    $id_user = $_POST['id_user'] ?? '';
                    $result = $controller->registrarAsistenciaQR($cod_evento, $id_user);
                    echo json_encode($result);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?> 