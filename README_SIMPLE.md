# Sistema de Gestión de Eventos - Versión Simplificada

## 📋 Descripción
Sistema web básico para gestionar eventos escolares, estudiantes y asistencia. Desarrollado en PHP con MySQL.

## 🎯 Funcionalidades Principales

### 👨‍💼 Administrador
- ✅ Gestionar personas (crear, editar, eliminar)
- ✅ Gestionar eventos (crear, editar, eliminar)
- ✅ Gestionar materias y grados
- ✅ Generar códigos QR
- ✅ Ver reportes

### 👨‍🎓 Estudiante
- ✅ Ver eventos disponibles
- ✅ Inscribirse en eventos
- ✅ Registrar asistencia
- ✅ Ver mi perfil

### 👨‍🏫 Docente
- ✅ Ver eventos asignados
- ✅ Registrar asistencia de estudiantes

## 🚀 Instalación Rápida

### 1. Configurar XAMPP
- Descarga e instala XAMPP
- Inicia Apache y MySQL

### 2. Configurar el Proyecto
```bash
# Copia el proyecto a htdocs
C:/xampp/htdocs/Recorded-world/
```

### 3. Configurar Base de Datos
1. Abre: `http://localhost/phpmyadmin/`
2. Crea base de datos: `registro`
3. Importa: `registro.sql`

### 4. Acceder al Sistema
- URL: `http://localhost/Recorded-world/public/`

## 🔐 Usuarios de Prueba

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| **Administrador** | `pedro@escuela.com` | `adm123` |
| **Docente** | `carlos@escuela.com` | `doc123` |
| **Estudiante** | `juan@mail.com` | `est123` |

## 📁 Estructura Simplificada

```
Recorded-world/
├── public/
│   ├── index.php          # Archivo principal
│   └── assets/
│       └── css/
│           └── style.css  # Estilos
├── src/
│   └── vistas/           # Páginas del sistema
│       ├── admin/        # Páginas del administrador
│       ├── estudiante/   # Páginas del estudiante
│       └── login.php     # Página de login
├── registro.sql          # Base de datos
└── README_SIMPLE.md      # Este archivo
```

## 🎨 Características del Diseño
- Diseño responsive (funciona en móvil y computadora)
- Colores modernos y atractivos
- Navegación fácil de usar
- Mensajes de confirmación

## 🛠️ Tecnologías Usadas
- **Backend:** PHP
- **Base de Datos:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Servidor:** XAMPP (Apache)

## 📱 Responsive Design
El sistema funciona en:
- 📱 Móviles
- 📱 Tablets  
- 💻 Computadoras

## 🎓 Para la Exposición

### Demo Sugerido:
1. **Login** con diferentes usuarios
2. **Dashboard** con estadísticas
3. **Gestión de Eventos** (crear, editar, eliminar)
4. **Gestión de Personas** (CRUD completo)
5. **Generación de QR**
6. **Registro de Asistencia**

### Puntos Destacados:
- ✅ Sistema de roles (Admin, Docente, Estudiante)
- ✅ CRUD completo de eventos y personas
- ✅ Generación de códigos QR
- ✅ Registro de asistencia
- ✅ Diseño responsive y moderno
- ✅ Fácil de usar y entender

## 🔧 Solución de Problemas

### Error de Conexión:
1. Verificar que MySQL esté corriendo
2. Comprobar que la base de datos `registro` existe
3. Verificar credenciales en `public/index.php`

### Error 404:
1. Verificar que el proyecto esté en `htdocs`
2. Comprobar que Apache esté corriendo

### Problemas de CSS:
1. Limpiar caché del navegador
2. Verificar que `assets/css/style.css` existe

---

**¡Tu sistema está listo para la exposición!** 🎉

**Funcionalidades básicas pero completas para graduarte exitosamente.** 