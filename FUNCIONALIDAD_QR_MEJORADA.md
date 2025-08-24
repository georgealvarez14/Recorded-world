# 📱 Funcionalidad de Códigos QR - Versión Mejorada

## 🎯 Descripción

Sistema simplificado y funcional para generar, gestionar y usar códigos QR en el sistema de eventos escolares.

## 🚀 Características Principales

### ✅ **Generación Individual**
- QR para cada persona específica
- QR para cada evento específico
- Generación desde el CRUD correspondiente

### ✅ **Generación Masiva**
- QR para todos los estudiantes de un grado
- Generación por lotes
- Proceso optimizado

### ✅ **Gestión Completa**
- Ver QR generados
- Descargar QR
- Eliminar QR individuales
- Información detallada

## 📁 Estructura de Archivos

```
src/controllers/
├── QRSimpleController.php    # Controlador simplificado
└── QRController.php          # Controlador original (backup)

uploads/qr/
├── personas/                 # QR de personas
│   ├── persona_1_Juan.png
│   ├── persona_2_Maria.png
│   └── ...
└── eventos/                  # QR de eventos
    ├── evento_EVT001_Olimpiadas.png
    ├── evento_EVT002_Conferencia.png
    └── ...
```

## 🛠️ Cómo Usar

### **1. Generar QR Individual para Persona**

#### Desde el CRUD de Personas:
1. Ve a **Gestionar Personas**
2. Busca la persona (estudiante)
3. Haz clic en el botón **📱 QR** (azul)
4. El QR se genera automáticamente
5. El botón cambia a verde (✅ QR generado)

#### Botones disponibles:
- **🔵 Generar QR**: Cuando no tiene QR
- **🟢 Ver QR**: Abre el QR en nueva pestaña
- **🔵 Ver Detalles**: Muestra información del QR
- **🔴 Eliminar QR**: Elimina el QR

### **2. Generar QR para Evento**

#### Desde el CRUD de Eventos:
1. Ve a **Gestionar Eventos**
2. Busca el evento
3. Haz clic en el botón **📱 QR** (amarillo)
4. El QR se genera automáticamente
5. El botón cambia a verde (✅ QR generado)

### **3. Generar QR Masivo por Grado**

#### Desde Generar QR:
1. Ve a **Generar QR** en el menú admin
2. Selecciona el grado del dropdown
3. Haz clic en **"Generar QR para el Grado"**
4. Se generan QR para todos los estudiantes del grado

## 📊 Información del QR

### **QR de Personas:**
```json
{
    "tipo": "persona",
    "id": "1",
    "nombre": "Juan Pérez",
    "tipo_persona": "EST",
    "grado": "6A",
    "fecha_generacion": "2025-01-28 15:30:00"
}
```

### **QR de Eventos:**
```json
{
    "tipo": "evento",
    "codigo": "EVT001",
    "nombre": "Olimpiadas Matemáticas",
    "fecha": "2025-02-15",
    "hora": "08:00:00",
    "ubicacion": "Auditorio Principal",
    "fecha_generacion": "2025-01-28 15:30:00"
}
```

## 🎨 Interfaz de Usuario

### **CRUD de Personas - Botones QR:**
```
┌─────────────────────────────────────┐
│ 👁️ Ver  ✏️ Editar  📱 QR  🗑️ Eliminar │
└─────────────────────────────────────┘

Estados del botón QR:
🔵 [📱] = Generar QR (no tiene QR)
🟢 [📱] = Ver QR (ya tiene QR)
🔵 [👁️] = Ver detalles del QR
🔴 [🗑️] = Eliminar QR
```

### **CRUD de Eventos - Botones QR:**
```
┌─────────────────────────────────────┐
│ 👁️ Ver  ✏️ Editar  📱 QR  🗑️ Eliminar │
└─────────────────────────────────────┘

Estados del botón QR:
🟡 [📱] = Generar QR (no tiene QR)
🟢 [📱] = Ver QR (ya tiene QR)
```

## 📱 Página de Ver QR

### **Información Mostrada:**
- ✅ Nombre de la persona
- ✅ ID de la persona
- ✅ Tipo de persona
- ✅ Archivo QR generado
- ✅ Fecha de generación
- ✅ Tamaño del archivo
- ✅ Imagen del QR

### **Acciones Disponibles:**
- ✅ **Descargar QR**: Descarga el archivo PNG
- ✅ **Abrir en Nueva Pestaña**: Ve el QR
- ✅ **Eliminar QR**: Elimina el QR
- ✅ **Volver a Personas**: Regresa al listado

## 🔧 Configuración Técnica

### **Controlador Simplificado:**
```php
// QRSimpleController.php
class QRSimpleController {
    // Generar QR para persona
    public function generarQRPersona($id_user)
    
    // Generar QR para evento
    public function generarQREvento($cod_evento)
    
    // Eliminar QR de persona
    public function eliminarQRPersona($id_user)
    
    // Generar QR masivo por grado
    public function generarQRMasivoGrado($cod_grado)
    
    // Verificar si tiene QR
    public function tieneQR($id_user)
    
    // Obtener información del QR
    public function obtenerInfoQR($id_user)
}
```

### **API Utilizada:**
```
https://api.qrserver.com/v1/create-qr-code/
?size=300x300&data=[contenido_json]
```

## 🚀 Rutas del Sistema

### **Generación Individual:**
```
index.php?accion=generar_qr_persona&id=[ID_PERSONA]
index.php?accion=generar_qr_evento&id=[COD_EVENTO]
```

### **Gestión:**
```
index.php?accion=ver_qr_persona&id=[ID_PERSONA]
index.php?accion=eliminar_qr_persona&id=[ID_PERSONA]
```

### **Generación Masiva:**
```
index.php?accion=generar_qr_masivo_grado
POST: grado=[COD_GRADO]
```

## 📋 Base de Datos

### **Tabla `persona`:**
```sql
ALTER TABLE persona ADD COLUMN codigo_qr VARCHAR(255) NULL;
```

### **Tabla `evento`:**
```sql
ALTER TABLE evento ADD COLUMN qr VARCHAR(255) NULL;
```

## 🎯 Casos de Uso

### **1. Control de Asistencia:**
1. Generar QR para todos los estudiantes
2. Imprimir QR y pegarlos en carnets
3. Escanear QR al entrar al evento
4. Registrar asistencia automáticamente

### **2. Identificación Rápida:**
1. QR individual para cada estudiante
2. Contiene información básica
3. Fácil identificación en eventos

### **3. Registro de Eventos:**
1. QR específico para cada evento
2. Escanear al llegar al evento
3. Verificar inscripción automáticamente

## 🔒 Seguridad

### **Validaciones Implementadas:**
- ✅ Verificación de permisos de administrador
- ✅ Validación de existencia de persona/evento
- ✅ Verificación de archivos antes de eliminar
- ✅ Limpieza de nombres de archivo

### **Protecciones:**
- ✅ Solo administradores pueden generar QR
- ✅ Verificación de sesión activa
- ✅ Validación de datos de entrada

## 🐛 Solución de Problemas

### **Error: "No se pudo generar el QR"**
- ✅ Verificar conexión a internet
- ✅ Verificar que la API esté disponible
- ✅ Revisar permisos de escritura en uploads/

### **Error: "Archivo no encontrado"**
- ✅ Verificar que el archivo existe físicamente
- ✅ Regenerar el QR si es necesario
- ✅ Revisar rutas de archivo

### **Error: "No se pudo guardar"**
- ✅ Verificar permisos de carpeta uploads/
- ✅ Verificar espacio en disco
- ✅ Revisar configuración de PHP

## 📈 Estadísticas

### **Información Mostrada:**
- 📊 Total de estudiantes
- 📊 QR generados
- 📊 Total de eventos
- 📊 QR de eventos generados

### **Contadores en Tiempo Real:**
- Estudiantes por grado
- QR generados por tipo
- Eventos con/sin QR

## 🎓 Para la Exposición

### **Demo Sugerido:**
1. **Mostrar CRUD de Personas**
   - Generar QR individual
   - Ver QR generado
   - Mostrar detalles del QR

2. **Mostrar CRUD de Eventos**
   - Generar QR para evento
   - Ver QR del evento

3. **Mostrar Generación Masiva**
   - Seleccionar grado
   - Generar QR para todo el grado
   - Mostrar estadísticas

4. **Mostrar Funcionalidades**
   - Descargar QR
   - Ver información detallada
   - Eliminar QR

### **Puntos Clave a Destacar:**
- ✅ **Simplicidad**: Fácil de usar y entender
- ✅ **Funcionalidad**: Todo funciona correctamente
- ✅ **Organización**: Archivos bien estructurados
- ✅ **Interfaz**: Diseño moderno y responsive
- ✅ **API Externa**: Uso de servicios gratuitos

---

## 📞 Soporte

### **Archivos Principales:**
- `src/controllers/QRSimpleController.php` - Lógica principal
- `src/vistas/admin/crud_personas.php` - Interfaz personas
- `src/vistas/admin/crud_eventos.php` - Interfaz eventos
- `src/vistas/admin/generar_qr.php` - Generación masiva
- `src/vistas/admin/ver_qr_persona.php` - Ver detalles QR

### **Configuración:**
- Verificar permisos de carpeta `uploads/qr/`
- Tener conexión a internet para generar QR
- Configurar base de datos con campos QR

---

**¡La funcionalidad de QR está lista y funcionando perfectamente!** 🎉 