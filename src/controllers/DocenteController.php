<?php
/**
 * Controlador de Docente Simplificado
 * Maneja las funcionalidades específicas de docentes
 */

class DocenteController {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Obtiene los estudiantes del grupo asignado al docente
     */
    public function getEstudiantesGrupo($docente_id) {
        try {
            // Obtener el grupo asignado al docente
            $stmt = $this->pdo->prepare("
                SELECT grupo_asignado FROM persona 
                WHERE id_user = ? AND tipo_persona = 'DOC'
            ");
            $stmt->execute([$docente_id]);
            $docente = $stmt->fetch();
            
            if (!$docente || !$docente['grupo_asignado']) {
                return [];
            }
            
            // Obtener estudiantes del grupo
            $stmt = $this->pdo->prepare("
                SELECT * FROM persona 
                WHERE tipo_persona = 'EST' 
                AND grupo = ? 
                ORDER BY nom_user
            ");
            $stmt->execute([$docente['grupo_asignado']]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error obteniendo estudiantes del grupo: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene todos los estudiantes (vista general)
     */
    public function getAllEstudiantes() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM persona 
                WHERE tipo_persona = 'EST' 
                ORDER BY nom_user
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo estudiantes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene estudiantes por grupo específico
     */
    public function getEstudiantesPorGrupo($grupo) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM persona 
                WHERE tipo_persona = 'EST' AND grupo = ?
                ORDER BY nom_user
            ");
            $stmt->execute([$grupo]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo estudiantes por grupo: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene todos los grupos disponibles
     */
    public function getGruposDisponibles() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT grupo FROM persona 
                WHERE tipo_persona = 'EST' AND grupo IS NOT NULL
                ORDER BY grupo
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error obteniendo grupos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Envía una petición de evento al administrador
     */
    public function enviarPeticionEvento($docente_id, $datos) {
        try {
            $sql = "INSERT INTO peticiones_evento (
                docente_id, nombre_evento, descripcion, fecha_propuesta, 
                hora_propuesta, ubicacion, materia, estado, fecha_peticion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDIENTE', NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $docente_id,
                $datos['nombre'],
                $datos['descripcion'],
                $datos['fecha'],
                $datos['hora'],
                $datos['ubicacion'],
                $datos['materia']
            ]);
            
            return [
                'success' => true,
                'message' => 'Petición de evento enviada exitosamente al administrador'
            ];
            
        } catch (Exception $e) {
            error_log("Error enviando petición: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al enviar petición: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene las peticiones de eventos del docente
     */
    public function getPeticionesEvento($docente_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM peticiones_evento 
                WHERE docente_id = ? 
                ORDER BY fecha_peticion DESC
            ");
            $stmt->execute([$docente_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo peticiones: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Registra la asistencia de un estudiante
     * El docente puede registrar asistencia de cualquier grupo con el que tenga clase
     */
    public function registrarAsistencia($evento_id, $estudiante_id, $docente_id, $asistio = true) {
        try {
            // Verificar que el estudiante existe
            $stmt = $this->pdo->prepare("
                SELECT id_user, nom_user, grupo, cod_grado 
                FROM persona 
                WHERE id_user = ? AND tipo_persona = 'EST'
            ");
            $stmt->execute([$estudiante_id]);
            $estudiante = $stmt->fetch();
            
            if (!$estudiante) {
                throw new Exception('Estudiante no encontrado');
            }
            
            // Registrar asistencia (sin restricción de grupo)
            $sql = "INSERT INTO asistencia_evento (
                cod_evento, id_user, docente_registro, asistio, fecha_registro
            ) VALUES (?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
                asistio = VALUES(asistio), 
                docente_registro = VALUES(docente_registro),
                fecha_registro = NOW()";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$evento_id, $estudiante_id, $docente_id, $asistio ? 1 : 0]);
            
            return [
                'success' => true,
                'message' => 'Asistencia registrada exitosamente para ' . $estudiante['nom_user'] . ' (Grupo: ' . $estudiante['grupo'] . ')'
            ];
            
        } catch (Exception $e) {
            error_log("Error registrando asistencia: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al registrar asistencia: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene el historial de asistencia de un estudiante
     */
    public function getHistorialAsistencia($estudiante_id, $docente_id) {
        try {
            // Verificar permisos
            $stmt = $this->pdo->prepare("
                SELECT p.grupo_asignado, e.grupo 
                FROM persona p 
                JOIN persona e ON e.grupo = p.grupo_asignado 
                WHERE p.id_user = ? AND e.id_user = ? AND p.tipo_persona = 'DOC'
            ");
            $stmt->execute([$docente_id, $estudiante_id]);
            $permiso = $stmt->fetch();
            
            if (!$permiso) {
                return [];
            }
            
            // Obtener historial
            $stmt = $this->pdo->prepare("
                SELECT ae.*, e.nom_evento, e.fecha_inicio 
                FROM asistencia_evento ae
                JOIN evento e ON ae.cod_evento = e.cod_evento
                WHERE ae.id_user = ?
                ORDER BY ae.fecha_registro DESC
            ");
            $stmt->execute([$estudiante_id]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error obteniendo historial: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene estadísticas del docente
     */
    public function getEstadisticasDocente($docente_id) {
        try {
            $stats = [];
            
            // Total de estudiantes en su grupo
            $estudiantes = $this->getEstudiantesGrupo($docente_id);
            $stats['total_estudiantes'] = count($estudiantes);
            
            // Peticiones enviadas
            $peticiones = $this->getPeticionesEvento($docente_id);
            $stats['total_peticiones'] = count($peticiones);
            $stats['peticiones_pendientes'] = count(array_filter($peticiones, function($p) {
                return $p['estado'] === 'PENDIENTE';
            }));
            
            // Total de asistencias registradas
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM asistencia_evento 
                WHERE docente_registro = ?
            ");
            $stmt->execute([$docente_id]);
            $stats['asistencias_registradas'] = $stmt->fetchColumn();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas docente: " . $e->getMessage());
            return [
                'total_estudiantes' => 0,
                'total_peticiones' => 0,
                'peticiones_pendientes' => 0,
                'asistencias_registradas' => 0
            ];
        }
    }
    
    /**
     * Registra asistencia por código QR
     */
    public function registrarAsistenciaQR($evento_id, $estudiante_id, $docente_id) {
        try {
            // Verificar que el evento existe
            $stmt = $this->pdo->prepare("
                SELECT cod_evento, nom_evento, fecha_inicio 
                FROM evento 
                WHERE cod_evento = ?
            ");
            $stmt->execute([$evento_id]);
            $evento = $stmt->fetch();
            
            if (!$evento) {
                throw new Exception('Evento no encontrado');
            }
            
            // Verificar que el estudiante existe
            $stmt = $this->pdo->prepare("
                SELECT id_user, nom_user, grupo, tipo_persona 
                FROM persona 
                WHERE id_user = ? AND tipo_persona = 'EST'
            ");
            $stmt->execute([$estudiante_id]);
            $estudiante = $stmt->fetch();
            
            if (!$estudiante) {
                throw new Exception('Estudiante no encontrado');
            }
            
            // Verificar si ya registró asistencia para este evento
            $stmt = $this->pdo->prepare("
                SELECT * FROM asistencia_evento 
                WHERE cod_evento = ? AND id_user = ?
            ");
            $stmt->execute([$evento_id, $estudiante_id]);
            $asistencia_existente = $stmt->fetch();
            
            if ($asistencia_existente) {
                // Ya registró asistencia - NO permitir duplicado
                return [
                    'success' => false,
                    'message' => 'El estudiante ' . $estudiante['nom_user'] . ' ya registró asistencia para este evento',
                    'tipo' => 'duplicado',
                    'estudiante' => $estudiante,
                    'evento' => $evento,
                    'fecha_registro' => $asistencia_existente['fecha_registro']
                ];
            } else {
                // Registrar nueva asistencia
                $stmt = $this->pdo->prepare("
                    INSERT INTO asistencia_evento (
                        cod_evento, id_user, docente_registro, asistio, fecha_registro
                    ) VALUES (?, ?, ?, 1, NOW())
                ");
                $stmt->execute([$evento_id, $estudiante_id, $docente_id]);
                
                return [
                    'success' => true,
                    'message' => 'Asistencia registrada exitosamente para ' . $estudiante['nom_user'] . ' (Grupo: ' . $estudiante['grupo'] . ')',
                    'tipo' => 'nueva',
                    'estudiante' => $estudiante,
                    'evento' => $evento
                ];
            }
            
        } catch (Exception $e) {
            error_log("Error registrando asistencia QR: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al registrar asistencia: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene registros recientes de asistencia del docente
     */
    public function getRegistrosRecientes($docente_id, $limite = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ae.*, p.nom_user, p.grupo, e.nom_evento 
                FROM asistencia_evento ae
                JOIN persona p ON ae.id_user = p.id_user
                JOIN evento e ON ae.cod_evento = e.cod_evento
                WHERE ae.docente_registro = ?
                ORDER BY ae.fecha_registro DESC
                LIMIT ?
            ");
            $stmt->execute([$docente_id, $limite]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error obteniendo registros recientes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verifica si un estudiante ya registró asistencia para un evento
     */
    public function verificarAsistenciaExistente($evento_id, $estudiante_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ae.*, p.nom_user, p.grupo, e.nom_evento 
                FROM asistencia_evento ae
                JOIN persona p ON ae.id_user = p.id_user
                JOIN evento e ON ae.cod_evento = e.cod_evento
                WHERE ae.cod_evento = ? AND ae.id_user = ?
            ");
            $stmt->execute([$evento_id, $estudiante_id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Error verificando asistencia existente: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene estadísticas de asistencia para un evento específico
     */
    public function getEstadisticasEvento($evento_id) {
        try {
            $stats = [];
            
            // Total de asistencias registradas
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM asistencia_evento 
                WHERE cod_evento = ?
            ");
            $stmt->execute([$evento_id]);
            $stats['total_asistencias'] = $stmt->fetchColumn();
            
            // Asistencias de hoy
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM asistencia_evento 
                WHERE cod_evento = ? AND DATE(fecha_registro) = CURDATE()
            ");
            $stmt->execute([$evento_id]);
            $stats['asistencias_hoy'] = $stmt->fetchColumn();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas del evento: " . $e->getMessage());
            return [
                'total_asistencias' => 0,
                'asistencias_hoy' => 0
            ];
        }
    }
}
?>
