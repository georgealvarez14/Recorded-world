<<<<<<< HEAD
# Sistema de Gestión de Eventos

Un sistema web moderno y elegante desarrollado en PHP para administrar eventos, personas y participantes en un entorno educativo.

## 🎨 **Nuevo Sistema de Diseño**

El proyecto ahora cuenta con un **sistema de CSS centralizado y moderno** que incluye:

### ✨ **Características del Diseño:**
- **Variables CSS** para consistencia en colores y estilos
- **Gradientes modernos** y efectos visuales atractivos
- **Diseño responsive** que funciona en móviles y tablets
- **Animaciones suaves** y transiciones elegantes
- **Iconos de Font Awesome** para mejor UX
- **Sombras y efectos** profesionales

### 🎯 **Colores del Sistema:**
- **Primario:** Azul púrpura (#667eea → #764ba2)
- **Éxito:** Verde (#28a745)
- **Advertencia:** Amarillo (#ffc107)
- **Peligro:** Rojo (#dc3545)
- **Info:** Azul (#17a2b8)

## 📁 Estructura del Proyecto

```
Recorded-world/
├── public/                 # Archivos públicos (punto de entrada)
│   ├── index.php          # Archivo principal
│   ├── assets/            # Recursos estáticos
│   │   └── css/
│   │       └── style.css  # CSS centralizado del sistema
│   └── .htaccess          # Configuración del servidor
├── src/                   # Código fuente
│   ├── config/           # Configuraciones
│   ├── controllers/      # Controladores
│   ├── models/          # Modelos
│   ├── views/           # Vistas
│   │   ├── auth/        # Páginas de autenticación
│   │   ├── home/        # Dashboard principal
│   │   ├── eventos/     # Gestión de eventos
│   │   ├── personas/    # Gestión de personas
│   │   └── materias/    # Gestión de materias
│   └── includes/        # Archivos incluidos
├── logs/                # Logs de la aplicación
├── uploads/             # Archivos subidos
├── database.sql         # Script de base de datos
└── vendor/              # Dependencias
```

## 🚀 Requisitos

- PHP 7.4 o superior
- MariaDB/MySQL 10.4 o superior
- Servidor web (Apache/Nginx)
- Extensión PDO habilitada en PHP

## ⚡ Instalación Rápida

### 1. **Configurar XAMPP:**
- Descarga e instala XAMPP
- Inicia **Apache** y **MySQL**

### 2. **Configurar el Proyecto:**
```bash
# Copia el proyecto a htdocs
cp -r Recorded-world/ C:/xampp/htdocs/

# O clona desde Git
git clone [tu-repositorio] C:/xampp/htdocs/Recorded-world
```

### 3. **Configurar la Base de Datos:**
1. Abre: `http://localhost/phpmyadmin/`
2. Crea una nueva base de datos llamada `registro`
3. Importa el archivo `registro.sql` (incluye datos de prueba)

### 4. **Acceder al Sistema:**
- URL: `http://localhost/Recorded-world/public/`
- ¡Listo para usar!

## 🔐 Credenciales de Acceso

### **Usuarios de Prueba:**

| Rol | Usuario | Contraseña | Funcionalidades |
|-----|---------|------------|-----------------|
| **Administrador** | `pedro@escuela.com` | `adm123` | Gestión completa del sistema |
| **Docente** | `carlos@escuela.com` | `doc123` | Gestión de eventos y asistencia |
| **Estudiante** | `juan@mail.com` | `est123` | Ver eventos y participar |
| **Acudiente** | `pass123` | `pass123` | Ver información de eventos |

## 🎯 Funcionalidades por Rol

### 👨‍💼 **Administrador (ADM)**
- ✅ Gestión completa de personas
- ✅ Crear y administrar eventos
- ✅ Gestionar materias
- ✅ Ver estadísticas del sistema
- ✅ Asignar personal a eventos

### 👨‍🏫 **Docente (DOC)**
- ✅ Ver eventos asignados
- ✅ Registrar asistencia de estudiantes
- ✅ Ver listas de participantes
- ✅ Gestionar sus propios eventos

### 👨‍🎓 **Estudiante (EST)**
- ✅ Ver eventos disponibles
- ✅ Inscribirse en eventos
- ✅ Ver eventos en los que participa
- ✅ Ver historial de asistencia

### 👨‍👩‍👧‍👦 **Acudiente (ACU)**
- ✅ Ver eventos disponibles
- ✅ Ver información de eventos
- ✅ Acceso limitado a información

## 🎨 Características del Diseño

### **Páginas Principales:**
1. **Dashboard Moderno** - Panel principal con estadísticas
2. **Gestión de Eventos** - Lista de tarjetas con filtros
3. **Gestión de Personas** - Vista de tarjetas y tabla
4. **Gestión de Materias** - Diseño dual (tarjetas + tabla)
5. **Login Elegante** - Diseño centrado con gradientes

### **Elementos de Diseño:**
- **Tarjetas animadas** con efectos hover
- **Gradientes modernos** en botones y headers
- **Iconos descriptivos** en toda la interfaz
- **Estados visuales** (activo, inactivo, próximo)
- **Responsive design** para todos los dispositivos

## 🔧 Personalización del CSS

El archivo `public/assets/css/style.css` contiene todas las variables CSS que puedes modificar:

```css
:root {
    /* Cambia los colores principales aquí */
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    
    /* Modifica los gradientes */
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    
    /* Ajusta las sombras */
    --shadow-md: 0 4px 20px rgba(0,0,0,0.1);
}
```

## 📱 Responsive Design

El sistema es completamente responsive y se adapta a:
- **Desktop** (1200px+)
- **Tablet** (768px - 1199px)
- **Mobile** (hasta 767px)

## 🎨 Componentes CSS Disponibles

### **Clases Utilitarias:**
- `.card-stats` - Tarjetas de estadísticas
- `.event-card` - Tarjetas de eventos
- `.person-card` - Tarjetas de personas
- `.search-container` - Contenedores de búsqueda
- `.welcome-section` - Secciones de bienvenida

### **Estados Visuales:**
- `.status-active` - Estado activo
- `.status-inactive` - Estado inactivo
- `.status-upcoming` - Eventos próximos
- `.status-past` - Eventos pasados

## 🚀 Para la Exposición

### **Puntos Destacados:**
1. **Diseño Moderno** - Interfaz profesional y atractiva
2. **Sistema de Roles** - Diferentes funcionalidades por usuario
3. **Responsive Design** - Funciona en todos los dispositivos
4. **CSS Centralizado** - Fácil de mantener y modificar
5. **Experiencia de Usuario** - Navegación intuitiva y fluida

### **Demo Sugerido:**
1. **Login** con diferentes usuarios
2. **Dashboard** con estadísticas
3. **Gestión de Eventos** con filtros
4. **Gestión de Personas** con búsqueda
5. **Responsive** en móvil/tablet

## 🛠️ Solución de Problemas

### **Error de Conexión a BD:**
1. Verificar que MySQL esté corriendo
2. Comprobar credenciales en `src/config/database.php`
3. Verificar que la base de datos `registro` existe

### **Error 404:**
1. Verificar que mod_rewrite esté habilitado
2. Comprobar configuración del DocumentRoot
3. Verificar archivo `.htaccess`

### **Problemas de CSS:**
1. Verificar que `assets/css/style.css` existe
2. Limpiar caché del navegador
3. Verificar rutas relativas

## 📞 Soporte

Para soporte técnico o reportar bugs:
- Revisar logs en la carpeta `logs/`
- Verificar configuración de PHP
- Comprobar permisos de archivos

---

**¡Tu sistema está listo para la exposición con un diseño profesional y moderno!** 🎉 
=======
# Recorded-world
>>>>>>> 3fa08e725e16763084526c4b2a9f239e5a8d17e9
