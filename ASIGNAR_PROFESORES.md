# 👨‍🏫 Asignar Profesores a Eventos

## ✅ **Nueva Funcionalidad Implementada**

El admin ahora puede asignar profesores a eventos específicos del sistema educativo.

## 🎯 **¿Qué hace esta funcionalidad?**

### **1. Asignación de Profesores:**
- ✅ El admin selecciona un evento
- ✅ El admin selecciona un profesor (solo tipo DOC)
- ✅ El sistema asigna el profesor al evento
- ✅ Se evita asignaciones duplicadas

### **2. Gestión de Asignaciones:**
- ✅ Ver todas las asignaciones actuales
- ✅ Desasignar profesores de eventos
- ✅ Agrupación por evento para mejor visualización

### **3. Validaciones:**
- ✅ Solo profesores (tipo DOC) pueden ser asignados
- ✅ No se puede asignar el mismo profesor dos veces al mismo evento
- ✅ Verificación de que el evento existe

## 🚀 **Cómo Usar la Funcionalidad**

### **1. Acceder como Admin:**
```
URL: http://localhost/Recorded-world/public/
Usuario: pedro@escuela.com
Contraseña: adm123
```

### **2. Ir a Asignar Profesores:**
- En el sidebar izquierdo, buscar "Asignaciones"
- Hacer clic en "Asignar Profesores"

### **3. Crear Nueva Asignación:**
- Seleccionar un evento del dropdown
- Seleccionar un profesor del dropdown
- Hacer clic en "Asignar Profesor"

### **4. Gestionar Asignaciones:**
- Ver todas las asignaciones en el panel derecho
- Usar el botón X para desasignar un profesor
- Confirmar la desasignación

## 📋 **Estructura de la Base de Datos**

### **Tabla `personal_evento`:**
```sql
CREATE TABLE `personal_evento` (
  `cod_evento` varchar(6) NOT NULL,
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`cod_evento`,`id_user`),
  FOREIGN KEY (`cod_evento`) REFERENCES `evento` (`cod_evento`),
  FOREIGN KEY (`id_user`) REFERENCES `persona` (`id_user`)
);
```

### **Relaciones:**
- **Evento** → `personal_evento` → **Persona (Profesor)**
- Solo personas con `tipo_persona = 'DOC'` pueden ser asignadas

## 👥 **Profesores Disponibles en el Sistema**

Según la base de datos `registro.sql`:

| ID | Nombre | Email | Tipo |
|----|--------|-------|------|
| 200 | Profesor Carlos | carlos@escuela.com | DOC |
| 201 | Profesora Ana | ana@escuela.com | DOC |
| 202 | Profesor Luis | luis@escuela.com | DOC |

## 📅 **Eventos Disponibles**

| Código | Nombre | Fecha | Materia |
|--------|--------|-------|---------|
| EVT001 | Olimpiadas Matemáticas | 15/11/2023 | Matemáticas |
| EVT002 | Feria Científica | 20/11/2023 | Ciencias |
| EVT003 | Taller de Historia | 25/11/2023 | Historia |

## 🎨 **Características de la Interfaz**

### **Diseño Simple y Funcional:**
- ✅ Formulario de asignación en el lado izquierdo
- ✅ Lista de asignaciones en el lado derecho
- ✅ Agrupación por evento para mejor organización
- ✅ Badges coloridos para identificar profesores
- ✅ Botones de acción claros

### **Validaciones en Tiempo Real:**
- ✅ Formulario con validación Bootstrap
- ✅ Mensajes de error y éxito
- ✅ Confirmación antes de desasignar

## 🔧 **Archivos Creados/Modificados**

### **Nuevos Archivos:**
- `src/controllers/AsignarProfesorController.php` - Lógica de asignación
- `src/views/admin/asignar-profesor.php` - Interfaz de usuario

### **Archivos Modificados:**
- `public/index.php` - Agregada ruta `asignar-profesor`
- `src/views/home/admin-dashboard.php` - Agregado enlace en sidebar

## 🧪 **Demo para la Exposición**

### **Paso 1: Mostrar el Dashboard Admin**
```
"Como admin, tengo acceso completo al sistema. Vean el panel de administración..."
```

### **Paso 2: Navegar a Asignar Profesores**
```
"En el sidebar, en la sección 'Asignaciones', tenemos 'Asignar Profesores'..."
```

### **Paso 3: Crear una Asignación**
```
"Voy a asignar el Profesor Carlos a las Olimpiadas Matemáticas..."
```

### **Paso 4: Mostrar las Asignaciones**
```
"Como pueden ver, ahora aparece en la lista de asignaciones..."
```

### **Paso 5: Desasignar**
```
"También puedo desasignar profesores si es necesario..."
```

## 💡 **Explicación Técnica Simple**

### **¿Cómo funciona?**
1. **Frontend**: Formulario HTML con Bootstrap
2. **Backend**: Controlador PHP que maneja la lógica
3. **Base de Datos**: Tabla `personal_evento` para las relaciones
4. **Validaciones**: Verificaciones en PHP y JavaScript

### **¿Por qué es útil?**
- Permite organizar quién está a cargo de cada evento
- Facilita la gestión de recursos humanos
- Evita conflictos de asignación
- Mantiene un registro claro de responsabilidades

## ✅ **Resultado**

Ahora el admin puede **asignar profesores a eventos** de manera simple y funcional, perfecto para explicar en la exposición.

---

**¡La funcionalidad está lista para usar!** 🎉 