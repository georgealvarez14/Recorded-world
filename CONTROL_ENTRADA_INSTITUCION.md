# 🏫 Control de Entrada a la Institución

## ✅ **Nueva Funcionalidad Implementada**

He implementado un sistema específico para el **control de entrada a la institución**, separado del registro de asistencia a eventos. Este sistema está diseñado exclusivamente para que el administrador controle la entrada diaria de estudiantes a la institución.

## 🎯 **Diferencias Clave**

### **Control de Entrada vs Asistencia a Eventos:**

| **Control de Entrada** | **Asistencia a Eventos** |
|------------------------|---------------------------|
| ✅ **Entrada diaria** a la institución | ✅ **Asistencia específica** a eventos |
| ✅ **Solo estudiantes** | ✅ **Cualquier persona** (estudiantes, docentes, etc.) |
| ✅ **Hora límite 8:00 AM** | ✅ **Sin límite de hora** |
| ✅ **Detección automática** de tardanzas | ✅ **Registro manual** por evento |
| ✅ **Estadísticas diarias** | ✅ **Estadísticas por evento** |
| ✅ **Solo administrador** | ✅ **Admin y docentes** |

## 🚀 **Características del Sistema**

### **1. Control Automático de Tardanzas**
- ✅ **Hora límite**: 8:00 AM
- ✅ **Detección automática**: Después de las 8:00 AM = tardanza
- ✅ **Registro de hora exacta**: Para auditoría
- ✅ **Prevención de duplicados**: Una entrada por día por estudiante

### **2. Escáner de Cámara Especializado**
- ✅ **Solo QR de estudiantes**: Rechaza QR de docentes/otros
- ✅ **Validación automática**: Verifica que sea estudiante
- ✅ **Registro instantáneo**: Sin selección de evento
- ✅ **Entrada manual**: Para casos especiales

### **3. Estadísticas en Tiempo Real**
- ✅ **Entradas del día**: Total de estudiantes que entraron
- ✅ **Tardanzas**: Estudiantes que llegaron después de las 8:00 AM
- ✅ **Ausentes**: Estudiantes que no han entrado hoy
- ✅ **Total de estudiantes**: En la institución

### **4. Interfaz Especializada**
- ✅ **Diseño verde**: Para diferenciar del sistema de eventos
- ✅ **Enfoque en estudiantes**: Solo muestra información relevante
- ✅ **Registro cronológico**: Entradas ordenadas por hora
- ✅ **Indicadores visuales**: Tardanzas vs entradas normales

## 📊 **Estructura de Base de Datos**

### **Nueva Tabla: `entrada_institucion`**
```sql
CREATE TABLE entrada_institucion (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_user int(11) NOT NULL,
  fecha_entrada date NOT NULL,
  hora_entrada time NOT NULL,
  tipo_entrada enum('normal','tardanza','ausente') DEFAULT 'normal',
  observaciones text,
  fecha_registro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES persona(id_user)
);
```

### **Campos Importantes:**
- **`fecha_entrada`**: Fecha de entrada (CURDATE())
- **`hora_entrada`**: Hora exacta de entrada (CURTIME())
- **`tipo_entrada`**: 'normal' (antes de 8:00) o 'tardanza' (después de 8:00)
- **`observaciones`**: Notas adicionales si es necesario

## 🎯 **Cómo Usar el Sistema**

### **Acceso:**
1. Inicia sesión como **Administrador**
2. Ve a **"CONTROL DE ENTRADA"** en el dashboard
3. La cámara se activará automáticamente

### **Proceso de Registro:**
1. **El estudiante muestra su QR** frente a la cámara
2. **El sistema detecta** automáticamente el código
3. **Valida que sea estudiante** (rechaza otros tipos)
4. **Verifica que no haya entrado** hoy
5. **Registra la entrada** con hora exacta
6. **Determina si es tardanza** (después de 8:00 AM)
7. **Muestra confirmación** inmediata

### **Entrada Manual:**
1. Ingresa el **ID del estudiante**
2. Haz clic en **"Registrar Entrada"**
3. El sistema aplica las mismas validaciones

## 📈 **Estadísticas Disponibles**

### **Panel de Estadísticas:**
- **Entradas Hoy**: Total de estudiantes que entraron hoy
- **Tardanzas**: Estudiantes que llegaron después de las 8:00 AM
- **Total Estudiantes**: Número total de estudiantes en la institución
- **Ausentes**: Estudiantes que no han entrado hoy

### **Registro de Entradas:**
- **Lista cronológica** de todas las entradas del día
- **Hora exacta** de cada entrada
- **Indicador visual** de tardanza vs entrada normal
- **Información del estudiante** (nombre, ID, grado)

## 🔧 **Validaciones de Seguridad**

### **Validaciones Implementadas:**
- ✅ **Solo administradores** pueden acceder
- ✅ **Solo estudiantes** pueden ser registrados
- ✅ **Una entrada por día** por estudiante
- ✅ **Verificación de existencia** del estudiante
- ✅ **Sanitización de datos** de entrada

### **Manejo de Errores:**
- ✅ **Estudiante no encontrado**
- ✅ **No es estudiante** (QR de docente/otro)
- ✅ **Ya entró hoy**
- ✅ **Error de cámara**
- ✅ **QR inválido**

## 🎨 **Interfaz de Usuario**

### **Diseño Especializado:**
- **Color verde**: Para diferenciar del sistema de eventos
- **Iconos específicos**: Puerta abierta, estudiante graduado
- **Enfoque claro**: "Control de Entrada - Institución"
- **Instrucciones específicas**: Para entrada a la institución

### **Elementos Visuales:**
- **Área de escaneo**: Con overlay verde
- **Estadísticas destacadas**: En panel lateral
- **Registro de entradas**: Con indicadores de tardanza
- **Notificaciones**: Específicas para entrada

## 📋 **Casos de Uso**

### **1. Entrada Matutina Normal:**
```
Escenario: "Estudiante llega a las 7:30 AM"
Proceso: 
1. Muestra QR frente a la cámara
2. Sistema registra entrada normal
3. Aparece en lista como "A tiempo"
```

### **2. Tardanza:**
```
Escenario: "Estudiante llega a las 8:15 AM"
Proceso:
1. Muestra QR frente a la cámara
2. Sistema detecta tardanza automáticamente
3. Aparece en lista como "Tardanza"
4. Se cuenta en estadísticas de tardanzas
```

### **3. Caso Especial:**
```
Escenario: "Estudiante sin QR o QR dañado"
Proceso:
1. Usar entrada manual
2. Ingresar ID del estudiante
3. Sistema aplica validaciones
4. Registra entrada con hora actual
```

## 🎯 **Demo para Exposición**

### **Paso 1: Mostrar la Diferencia**
```
"Tenemos dos sistemas separados: Control de Entrada y Asistencia a Eventos"
[Mostrar ambos botones en el dashboard]
```

### **Paso 2: Control de Entrada**
```
"El Control de Entrada es específico para la entrada diaria a la institución"
[Mostrar la página de control de entrada]
```

### **Paso 3: Demostrar Escaneo**
```
"Los estudiantes muestran su QR y se registra automáticamente su entrada"
[Mostrar escaneo de QR de estudiante]
```

### **Paso 4: Mostrar Tardanza**
```
"Si llegan después de las 8:00 AM, se marca automáticamente como tardanza"
[Mostrar registro de tardanza]
```

### **Paso 5: Estadísticas**
```
"El sistema mantiene estadísticas en tiempo real de entradas y tardanzas"
[Mostrar panel de estadísticas]
```

## ✅ **Beneficios del Sistema**

1. **Especialización**: Sistema dedicado para entrada a la institución
2. **Automatización**: Detección automática de tardanzas
3. **Control**: Una entrada por día por estudiante
4. **Trazabilidad**: Registro de hora exacta
5. **Estadísticas**: Información en tiempo real
6. **Seguridad**: Solo estudiantes, solo administradores

## 🔒 **Seguridad y Auditoría**

### **Medidas de Seguridad:**
- ✅ **Control de acceso** por rol de usuario
- ✅ **Validación de tipo** de persona (solo estudiantes)
- ✅ **Prevención de duplicados** diarios
- ✅ **Logs de auditoría** para seguimiento
- ✅ **Sanitización** de datos de entrada

### **Información de Auditoría:**
- ✅ **Hora exacta** de entrada
- ✅ **Tipo de entrada** (normal/tardanza)
- ✅ **Usuario que registró** (admin)
- ✅ **Fecha de registro** automática
- ✅ **Observaciones** opcionales

Este sistema proporciona un control completo y especializado para la entrada diaria de estudiantes a la institución, con detección automática de tardanzas y estadísticas en tiempo real. 