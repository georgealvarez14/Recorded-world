# Corrección de Error de Integridad Referencial

## Problema
Se presentaban errores de integridad referencial al intentar eliminar eventos o personas debido a restricciones de claves foráneas en las tablas relacionadas.

### Errores específicos:
1. `FOREIGN KEY constraint fails (participante, CONSTRAINT participante_ibfk_2 FOREIGN KEY (cod_evento) REFERENCES evento (cod_evento))`
2. `FOREIGN KEY constraint fails (personal_evento, CONSTRAINT personal_evento_ibfk_1 FOREIGN KEY (cod_evento) REFERENCES evento (cod_evento))`

## Solución Implementada

### 1. Eliminación de Eventos (`eliminar_evento_admin`)
Se modificó el caso en `public/index.php` para usar transacciones PDO y eliminar en el orden correcto:

```php
case 'eliminar_evento_admin':
    // ... validaciones de seguridad ...
    
    try {
        $pdo->beginTransaction();
        
        // 1. Eliminar participantes del evento
        $stmt = $pdo->prepare("DELETE FROM participante WHERE cod_evento = ?");
        $stmt->execute([$id]);
        
        // 2. Eliminar personal del evento
        $stmt = $pdo->prepare("DELETE FROM personal_evento WHERE cod_evento = ?");
        $stmt->execute([$id]);
        
        // 3. Eliminar archivo QR del evento si existe
        $stmt = $pdo->prepare("SELECT qr FROM evento WHERE cod_evento = ?");
        $stmt->execute([$id]);
        $evento = $stmt->fetch();
        
        if ($evento && $evento['qr'] && file_exists($evento['qr'])) {
            unlink($evento['qr']);
        }
        
        // 4. Eliminar el evento
        $stmt = $pdo->prepare("DELETE FROM evento WHERE cod_evento = ?");
        $stmt->execute([$id]);
        
        $pdo->commit();
        $_SESSION['success'] = 'Evento eliminado exitosamente junto con todos sus participantes y personal';
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Error al eliminar el evento: ' . $e->getMessage();
    }
```

### 2. Eliminación de Personas (`eliminar_persona_admin`)
Se modificó el caso en `public/index.php` para usar transacciones PDO:

```php
case 'eliminar_persona_admin':
    // ... validaciones de seguridad ...
    
    try {
        $pdo->beginTransaction();
        
        // 1. Eliminar participaciones de la persona
        $stmt = $pdo->prepare("DELETE FROM participante WHERE id_user = ?");
        $stmt->execute([$id]);
        
        // 2. Eliminar archivo QR de la persona si existe
        $stmt = $pdo->prepare("SELECT codigo_qr FROM persona WHERE id_user = ?");
        $stmt->execute([$id]);
        $persona = $stmt->fetch();
        
        if ($persona && $persona['codigo_qr'] && file_exists($persona['codigo_qr'])) {
            unlink($persona['codigo_qr']);
        }
        
        // 3. Eliminar la persona
        $stmt = $pdo->prepare("DELETE FROM persona WHERE id_user = ?");
        $stmt->execute([$id]);
        
        $pdo->commit();
        $_SESSION['success'] = 'Persona eliminada exitosamente junto con todas sus participaciones';
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Error al eliminar la persona: ' . $e->getMessage();
    }
```

## Beneficios de la Solución

1. **Integridad de Datos**: Se mantiene la integridad referencial de la base de datos
2. **Transacciones**: Uso de transacciones PDO para operaciones atómicas
3. **Limpieza de Archivos**: Eliminación automática de archivos QR asociados
4. **Manejo de Errores**: Rollback automático en caso de fallo
5. **Mensajes Informativos**: Feedback claro al usuario sobre el resultado

## Orden de Eliminación

### Para Eventos:
1. `participante` (participantes del evento)
2. `personal_evento` (personal asignado al evento)
3. Archivo QR del evento
4. `evento` (evento principal)

### Para Personas:
1. `participante` (participaciones de la persona)
2. Archivo QR de la persona
3. `persona` (persona principal)

## Notas Técnicas

- Se utiliza `PDO::beginTransaction()` y `PDO::commit()` para transacciones
- Se implementa `PDO::rollBack()` en caso de excepción
- Se verifica la existencia de archivos antes de intentar eliminarlos con `file_exists()`
- Se mantienen las validaciones de seguridad existentes 