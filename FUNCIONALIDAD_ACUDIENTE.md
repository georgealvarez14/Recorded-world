# Funcionalidad de Acudiente (Guardian)

## Descripción General

Se ha implementado la funcionalidad completa para acudientes (guardianes) que les permite ver únicamente la información de los estudiantes bajo su cargo. Esta funcionalidad incluye un dashboard personalizado, gestión de perfiles y visualización detallada de la participación en eventos.

## Características Implementadas

### 1. Dashboard de Acudiente (`dashboard_acudiente`)
- **Ubicación**: `src/vistas/acudiente/dashboard.php`
- **Funcionalidades**:
  - Estadísticas rápidas de estudiantes a cargo
  - Lista completa de estudiantes con información básica
  - Enlaces directos a información detallada de cada estudiante
  - Información personal del acudiente
  - Navegación intuitiva con iconos FontAwesome

### 2. Vista Detallada de Estudiante (`ver_estudiante_acudiente`)
- **Ubicación**: `src/vistas/acudiente/ver_estudiante.php`
- **Funcionalidades**:
  - Información personal completa del estudiante
  - Estadísticas de participación en eventos
  - Eventos recientes (últimos 5)
  - Enlaces para ver todos los eventos del estudiante
  - Validación de permisos (solo estudiantes bajo su cargo)

### 3. Eventos del Estudiante (`eventos_estudiante_acudiente`)
- **Ubicación**: `src/vistas/acudiente/eventos_estudiante.php`
- **Funcionalidades**:
  - Pestañas organizadas por tipo de evento:
    - Próximos eventos
    - Eventos asistidos
    - Eventos pasados
  - Estadísticas detalladas de participación
  - Información completa de cada evento
  - Estados de asistencia claramente marcados

### 4. Edición de Perfil (`editar_perfil_acudiente`)
- **Ubicación**: `src/vistas/acudiente/editar_perfil.php`
- **Funcionalidades**:
  - Actualización de información personal
  - Cambio de contraseña con validaciones
  - Selección de ciudad desde base de datos
  - Validación de email único
  - Consejos de seguridad integrados

## Estructura de Archivos

```
src/vistas/acudiente/
├── dashboard.php              # Dashboard principal
├── ver_estudiante.php         # Vista detallada de estudiante
├── eventos_estudiante.php     # Eventos de un estudiante
└── editar_perfil.php          # Edición de perfil
```

## Rutas Implementadas

### Rutas Principales
- `dashboard_acudiente` - Dashboard principal
- `ver_estudiante_acudiente` - Ver información de estudiante
- `eventos_estudiante_acudiente` - Ver eventos de estudiante
- `editar_perfil_acudiente` - Editar perfil personal
- `actualizar_perfil_acudiente` - Procesar actualización de perfil

### Seguridad
- Todas las rutas verifican que el usuario sea de tipo 'ACU'
- Validación de permisos para ver solo estudiantes bajo su cargo
- Verificación de propiedad de datos antes de mostrar información

## Controlador de Acudientes

### Ubicación
`src/controllers/AcudienteController.php`

### Métodos Principales
- `getEstudiantes($acudiente_id)` - Obtiene estudiantes bajo cargo
- `getEventosEstudiante($estudiante_id)` - Obtiene eventos de un estudiante
- `getEstadisticasEstudiante($estudiante_id)` - Estadísticas de participación
- `getInformacionAcudiente($acudiente_id)` - Información del acudiente
- `getResumenAcudiente($acudiente_id)` - Resumen general

## Base de Datos

### Relación Estudiante-Acudiente
Los estudiantes están vinculados a acudientes a través del campo `cc_acudiente` en la tabla `persona`:

```sql
-- Ejemplo de consulta para obtener estudiantes de un acudiente
SELECT p.* FROM persona p 
WHERE p.cc_acudiente = ? AND p.tipo_persona = 'EST'
```

### Tablas Utilizadas
- `persona` - Información de estudiantes y acudientes
- `participante` - Inscripciones a eventos
- `registro_participante` - Asistencias registradas
- `evento` - Información de eventos
- `grado` - Grados académicos
- `ciudad` - Ciudades disponibles

## Características de Seguridad

### 1. Validación de Permisos
- Verificación de tipo de usuario en cada ruta
- Validación de propiedad de datos antes de mostrar información
- Redirección automática si no tiene permisos

### 2. Protección de Datos
- Los acudientes solo ven información de sus estudiantes
- Validación de ID de estudiante antes de mostrar datos
- Sanitización de datos de entrada

### 3. Gestión de Sesiones
- Verificación de sesión activa
- Actualización de información de sesión al cambiar perfil
- Cierre de sesión seguro

## Interfaz de Usuario

### Diseño Responsivo
- Utiliza Bootstrap 5.1.3 para diseño responsivo
- Iconos FontAwesome 6.0.0 para mejor UX
- Colores distintivos para acudientes (amarillo/warning)

### Navegación Intuitiva
- Breadcrumbs y enlaces de retorno
- Botones de acción claramente identificados
- Mensajes de éxito/error informativos

### Características Visuales
- Tarjetas organizadas por funcionalidad
- Estadísticas con iconos y colores distintivos
- Tablas responsivas para información detallada
- Pestañas para organizar contenido extenso

## Flujo de Usuario

### 1. Login
- El acudiente inicia sesión con sus credenciales
- Es redirigido automáticamente al dashboard de acudiente

### 2. Dashboard Principal
- Ve estadísticas generales de sus estudiantes
- Accede a lista de estudiantes bajo su cargo
- Navega a funcionalidades específicas

### 3. Gestión de Estudiantes
- Selecciona un estudiante para ver información detallada
- Accede a eventos específicos del estudiante
- Revisa estadísticas de participación

### 4. Gestión de Perfil
- Actualiza información personal
- Cambia contraseña si es necesario
- Mantiene datos de contacto actualizados

## Mensajes y Notificaciones

### Tipos de Mensajes
- **Éxito**: Confirmación de acciones completadas
- **Error**: Notificación de problemas o errores
- **Información**: Consejos y guías para el usuario

### Ejemplos de Mensajes
- "Perfil actualizado exitosamente"
- "No tienes permisos para ver este estudiante"
- "La contraseña actual es incorrecta"

## Consideraciones Técnicas

### Rendimiento
- Consultas optimizadas con JOINs apropiados
- Paginación implícita en listas
- Carga diferida de información detallada

### Mantenibilidad
- Código modular y bien organizado
- Separación clara de responsabilidades
- Documentación inline en funciones críticas

### Escalabilidad
- Estructura preparada para futuras funcionalidades
- Controlador reutilizable para otras funcionalidades
- Base de datos normalizada para eficiencia

## Próximas Mejoras Sugeridas

1. **Notificaciones Push**: Alertas en tiempo real para eventos próximos
2. **Reportes PDF**: Generación de reportes de participación
3. **Calendario Integrado**: Vista de calendario de eventos
4. **Comunicación**: Sistema de mensajes entre acudiente y administración
5. **Móvil**: Aplicación móvil para acceso desde dispositivos móviles

## Conclusión

La funcionalidad de acudiente proporciona una experiencia completa y segura para que los guardianes puedan monitorear la participación de sus estudiantes en eventos escolares. La implementación incluye todas las características necesarias para una gestión efectiva, con un enfoque en la seguridad, usabilidad y escalabilidad. 