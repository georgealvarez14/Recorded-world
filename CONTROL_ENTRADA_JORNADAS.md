# 🏫 Control de Entrada con Jornadas (Mañana/Tarde)

## ✅ **Nueva Funcionalidad Implementada**

He actualizado el sistema de control de entrada para manejar **jornadas diferenciadas** (mañana y tarde) con sus respectivos horarios de tolerancia para tardanzas.

## 🎯 **Horarios de Jornadas**

### **Jornada de Mañana:**
- ✅ **Hora de entrada**: 6:00 AM - 11:59 AM
- ✅ **Límite de tiempo**: 6:15 AM
- ✅ **Antes de 6:15 AM**: Entrada a tiempo ✅
- ✅ **Después de 6:15 AM**: Tardanza ⚠️

### **Jornada de Tarde:**
- ✅ **Hora de entrada**: 12:00 PM - 5:59 PM
- ✅ **Límite de tiempo**: 12:30 PM
- ✅ **Antes de 12:30 PM**: Entrada a tiempo ✅
- ✅ **Después de 12:30 PM**: Tardanza ⚠️

## 📊 **Estructura de Base de Datos Actualizada**

### **Nueva Tabla: `entrada_institucion`**
```sql
CREATE TABLE entrada_institucion (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_user int(11) NOT NULL,
  fecha_entrada date NOT NULL,
  hora_entrada time NOT NULL,
  jornada enum('mañana','tarde') NOT NULL,
  tipo_entrada enum('normal','tardanza','ausente') DEFAULT 'normal',
  observaciones text,
  fecha_registro timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES persona(id_user)
);
```

### **Campos Nuevos:**
- **`jornada`**: 'mañana' o 'tarde' (detectado automáticamente)
- **`hora_entrada`**: Hora exacta de entrada
- **`tipo_entrada`**: 'normal' o 'tardanza' (según horarios)

## 🚀 **Características del Sistema**

### **1. Detección Automática de Jornada**
- ✅ **Mañana**: 6:00 AM - 11:59 AM
- ✅ **Tarde**: 12:00 PM - 5:59 PM
- ✅ **Detección automática** según hora actual
- ✅ **Validación de horarios** específicos por jornada

### **2. Control de Tardanzas por Jornada**
- ✅ **Mañana**: Tardanza después de 6:15 AM
- ✅ **Tarde**: Tardanza después de 12:30 PM
- ✅ **Registro automático** del tipo de entrada
- ✅ **Prevención de duplicados** por jornada

### **3. Estadísticas Diferenciadas**
- ✅ **Entradas por jornada** (mañana/tarde)
- ✅ **Tardanzas por jornada** (mañana/tarde)
- ✅ **Total de entradas** del día
- ✅ **Estudiantes ausentes** del día

### **4. Interfaz Actualizada**
- ✅ **Indicador de jornada actual** en estadísticas
- ✅ **Badges de jornada** en lista de entradas
- ✅ **Instrucciones claras** con horarios
- ✅ **Estadísticas separadas** por jornada

## 📈 **Estadísticas Disponibles**

### **Panel de Estadísticas:**
- **Jornada Actual**: Muestra si es mañana o tarde
- **Total Entradas**: Suma de ambas jornadas
- **Total Tardanzas**: Suma de tardanzas de ambas jornadas
- **Entradas Mañana**: Solo entradas de la jornada matutina
- **Entradas Tarde**: Solo entradas de la jornada vespertina
- **Tardanzas Mañana**: Tardanzas de la jornada matutina
- **Tardanzas Tarde**: Tardanzas de la jornada vespertina
- **Total Estudiantes**: Número total de estudiantes
- **Ausentes**: Estudiantes que no han entrado hoy

### **Registro de Entradas:**
- **Ordenado por jornada**: Mañana primero, luego tarde
- **Hora exacta**: De cada entrada
- **Badge de jornada**: Azul para mañana, celeste para tarde
- **Indicador de tardanza**: Amarillo para tardanzas
- **Información completa**: Nombre, ID, grado

## 🔧 **Lógica de Validación**

### **Detección de Jornada:**
```php
$hora_actual = date('H:i:s');
$jornada_actual = ($hora_actual >= '06:00:00' && $hora_actual < '12:00:00') ? 'mañana' : 'tarde';
```

### **Determinación de Tardanza:**
```php
$tipo_entrada = 'normal';
if ($jornada_actual === 'mañana' && $hora_actual > '06:15:00') {
    $tipo_entrada = 'tardanza';
} elseif ($jornada_actual === 'tarde' && $hora_actual > '12:30:00') {
    $tipo_entrada = 'tardanza';
}
```

### **Prevención de Duplicados:**
```php
$entrada_existente = $db->fetch(
    "SELECT id FROM entrada_institucion 
     WHERE id_user = ? AND fecha_entrada = CURDATE() AND jornada = ?", 
    [$id_user, $jornada_actual]
);
```

## 📋 **Casos de Uso**

### **1. Entrada Matutina a Tiempo:**
```
Escenario: "Estudiante llega a las 6:00 AM"
Proceso: 
1. Sistema detecta jornada "mañana"
2. Hora < 6:15 AM = entrada normal
3. Se registra como "normal" en jornada "mañana"
```

### **2. Tardanza Matutina:**
```
Escenario: "Estudiante llega a las 6:20 AM"
Proceso:
1. Sistema detecta jornada "mañana"
2. Hora > 6:15 AM = tardanza
3. Se registra como "tardanza" en jornada "mañana"
```

### **3. Entrada Vespertina a Tiempo:**
```
Escenario: "Estudiante llega a las 12:15 PM"
Proceso:
1. Sistema detecta jornada "tarde"
2. Hora < 12:30 PM = entrada normal
3. Se registra como "normal" en jornada "tarde"
```

### **4. Tardanza Vespertina:**
```
Escenario: "Estudiante llega a las 12:35 PM"
Proceso:
1. Sistema detecta jornada "tarde"
2. Hora > 12:30 PM = tardanza
3. Se registra como "tardanza" en jornada "tarde"
```

### **5. Entrada en Ambas Jornadas:**
```
Escenario: "Estudiante entra en la mañana y luego en la tarde"
Proceso:
1. Mañana: Se registra entrada matutina
2. Tarde: Se registra entrada vespertina (permitido)
3. Cada jornada es independiente
```

## 🎯 **Demo para Exposición**

### **Paso 1: Mostrar Jornadas**
```
"El sistema ahora maneja jornadas diferenciadas: mañana y tarde"
[Mostrar estadísticas con jornada actual]
```

### **Paso 2: Demostrar Horarios**
```
"En la mañana, antes de las 6:15 AM es a tiempo, después es tardanza"
[Mostrar entrada matutina]
```

### **Paso 3: Cambiar Jornada**
```
"En la tarde, antes de las 12:30 PM es a tiempo, después es tardanza"
[Mostrar entrada vespertina]
```

### **Paso 4: Estadísticas por Jornada**
```
"Las estadísticas se separan por jornada para mejor control"
[Mostrar panel de estadísticas diferenciadas]
```

### **Paso 5: Prevención de Duplicados**
```
"Un estudiante puede entrar en ambas jornadas, pero no dos veces en la misma"
[Mostrar validación de duplicados]
```

## ✅ **Beneficios del Sistema**

1. **Flexibilidad**: Manejo de jornadas diferenciadas
2. **Precisión**: Horarios específicos por jornada
3. **Control**: Prevención de duplicados por jornada
4. **Estadísticas**: Información separada por jornada
5. **Automatización**: Detección automática de jornada y tardanzas
6. **Trazabilidad**: Registro completo de entradas por jornada

## 🔒 **Seguridad y Validaciones**

### **Validaciones Implementadas:**
- ✅ **Detección automática** de jornada
- ✅ **Validación de horarios** específicos
- ✅ **Prevención de duplicados** por jornada
- ✅ **Solo estudiantes** pueden ser registrados
- ✅ **Solo administradores** pueden acceder

### **Información de Auditoría:**
- ✅ **Jornada registrada** automáticamente
- ✅ **Hora exacta** de entrada
- ✅ **Tipo de entrada** según horarios
- ✅ **Fecha de registro** automática
- ✅ **Usuario que registró** (admin)

Este sistema proporciona un control completo y especializado para la entrada de estudiantes por jornadas, con detección automática de tardanzas según los horarios específicos de cada jornada. 