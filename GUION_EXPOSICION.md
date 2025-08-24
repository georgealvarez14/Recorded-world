# 🎓 Guión para Exposición - Sistema de Gestión de Eventos

## 📝 **Guión Completo (5-7 minutos)**

---

## 🎯 **INTRODUCCIÓN (1 minuto)**

### **Saludo:**
"Buenos días/tardes, mi nombre es [TU NOMBRE] y hoy les voy a presentar mi proyecto final: **Sistema de Gestión de Eventos Educativos**."

### **¿Qué es el proyecto?**
"Este es un sistema web desarrollado en PHP que permite gestionar eventos, personas y materias en un entorno educativo. Es un proyecto básico pero funcional, perfecto para demostrar mis conocimientos como programador junior."

### **Tecnologías utilizadas:**
"Para desarrollar este sistema utilicé:
- **PHP** para la lógica del servidor
- **MySQL** para la base de datos
- **Bootstrap** para el diseño
- **HTML/CSS** para la interfaz"

---

## 🖥️ **DEMOSTRACIÓN PRÁCTICA (4-5 minutos)**

### **1. Página de Login (1 minuto)**

**Acción:** Abrir el navegador y ir a `http://localhost/Recorded-world/public/`

**Explicación:**
"Primero vemos la página de login. Aquí los usuarios pueden entrar al sistema con su correo y contraseña."

**Acción:** Hacer login con:
- Usuario: `pedro@escuela.com`
- Contraseña: `adm123`

**Explicación:**
"Como pueden ver, el sistema valida las credenciales y nos lleva al dashboard principal."

---

### **2. Dashboard Principal (1 minuto)**

**Explicación:**
"Esta es la página principal del sistema. Aquí vemos:
- El nombre del usuario que está logueado
- Las opciones disponibles según el tipo de usuario
- Tarjetas que nos llevan a diferentes secciones"

**Acción:** Mostrar las tarjetas de Personas, Eventos, Materias

**Explicación:**
"Cada tarjeta representa una sección del sistema. El administrador tiene acceso a todas las funciones."

---

### **3. Sección de Personas (1 minuto)**

**Acción:** Hacer clic en "Personas"

**Explicación:**
"En esta sección podemos ver todas las personas registradas en el sistema: estudiantes, docentes y administrativos."

**Acción:** Mostrar la lista de personas

**Explicación:**
"Como pueden ver, tenemos una tabla con la información de cada persona. Podemos buscar, filtrar y editar los datos."

**Acción:** Hacer clic en "Editar" en una persona

**Explicación:**
"Al hacer clic en editar, nos lleva a un formulario donde podemos modificar la información de la persona. El formulario tiene validaciones para asegurar que los datos sean correctos."

**Acción:** Volver a la lista de personas

---

### **4. Sección de Eventos (1 minuto)**

**Acción:** Hacer clic en "Eventos"

**Explicación:**
"En la sección de eventos vemos todos los eventos programados en el sistema."

**Acción:** Mostrar la lista de eventos

**Explicación:**
"Cada evento muestra información como el título, fecha, ubicación y estado. Los eventos pueden estar activos, pasados o próximos."

**Acción:** Hacer clic en "Nuevo Evento"

**Explicación:**
"Para crear un nuevo evento, llenamos este formulario con la información necesaria. El sistema valida que todos los campos obligatorios estén completos."

**Acción:** Cancelar y volver a la lista

---

### **5. Sección de Materias (1 minuto)**

**Acción:** Hacer clic en "Materias"

**Explicación:**
"En materias gestionamos las asignaturas del colegio. Aquí vemos las materias existentes y podemos agregar nuevas."

**Acción:** Mostrar la lista de materias

**Explicación:**
"Cada materia tiene un código único y una descripción. Por ejemplo: MA para Matemáticas, CI para Ciencias."

**Acción:** Hacer clic en "Nueva Materia"

**Explicación:**
"Para crear una nueva materia, ingresamos el código y la descripción. El sistema verifica que el código no esté duplicado."

**Acción:** Cancelar y volver

---

## 💻 **EXPLICACIÓN TÉCNICA SIMPLE (1 minuto)**

### **¿Cómo funciona?**
"El sistema funciona de manera simple:
1. **El usuario** hace clic en un enlace
2. **El servidor** recibe la petición
3. **PHP** procesa la información
4. **MySQL** guarda o recupera datos
5. **El navegador** muestra el resultado"

### **Base de datos:**
"La información se guarda en tablas simples:
- **persona**: datos de estudiantes y docentes
- **evento**: información de eventos
- **materias**: asignaturas del colegio"

### **Seguridad básica:**
"El sistema tiene validaciones básicas:
- Verifica que el usuario esté logueado
- Valida los datos de entrada
- Usa consultas preparadas para evitar inyección SQL"

---

## 🎯 **CONCLUSIÓN (30 segundos)**

### **Lo que aprendí:**
"Con este proyecto aprendí:
- Cómo conectar PHP con MySQL
- Cómo crear formularios y validarlos
- Cómo hacer páginas web responsivas
- Cómo organizar código de manera básica"

### **Mejoras futuras:**
"El sistema se puede mejorar agregando:
- Más validaciones
- Reportes y estadísticas
- Mejor diseño
- Más funcionalidades"

### **Cierre:**
"Este es mi proyecto de Sistema de Gestión de Eventos. Es simple pero funcional, y demuestra mis conocimientos básicos en desarrollo web. ¿Hay alguna pregunta?"

---

## ❓ **POSIBLES PREGUNTAS Y RESPUESTAS**

### **¿Por qué usaste PHP?**
"PHP es fácil de aprender para principiantes y es muy usado en desarrollo web. Es perfecto para proyectos básicos como este."

### **¿Es seguro el sistema?**
"Tiene validaciones básicas y usa consultas preparadas. Para un proyecto de aprendizaje es suficiente, pero en producción se necesitarían más medidas de seguridad."

### **¿Se puede usar en móviles?**
"Sí, usa Bootstrap que hace que se adapte a cualquier pantalla. Es responsive."

### **¿Qué base de datos usaste?**
"MySQL, que es la base de datos más común para PHP. Es fácil de usar y aprender."

### **¿Cuánto tiempo te tomó?**
"Aproximadamente [X] semanas, trabajando unas horas al día. Es un proyecto básico pero completo."

### **¿Qué fue lo más difícil?**
"Entender cómo conectar PHP con la base de datos y hacer que los formularios funcionen correctamente."

### **¿Qué te gustaría mejorar?**
"Agregar más validaciones, hacer el diseño más bonito, y agregar reportes y estadísticas."

---

## 🚀 **CONSEJOS PARA LA EXPOSICIÓN**

### **Antes de la exposición:**
1. **Practica el demo** varias veces
2. **Ten las credenciales** escritas en un papel
3. **Asegúrate** de que XAMPP esté funcionando
4. **Prueba** que todo funcione correctamente

### **Durante la exposición:**
1. **Habla despacio** y con claridad
2. **Explica cada paso** que haces
3. **Mantén la calma** si algo no funciona
4. **Enfócate en la funcionalidad**, no en el código complejo

### **Si algo falla:**
1. **No te pongas nervioso**
2. **Explica** que es normal en desarrollo
3. **Continúa** con la explicación
4. **Muestra** las partes que sí funcionan

---

## 📋 **CHECKLIST ANTES DE LA EXPOSICIÓN**

- [ ] XAMPP instalado y funcionando
- [ ] Base de datos importada
- [ ] Credenciales de login listas
- [ ] Demo practicado varias veces
- [ ] Navegador abierto en la página correcta
- [ ] Guión memorizado
- [ ] Respuestas a preguntas preparadas

---

**¡Tu exposición está lista! Recuerda: es mejor mostrar algo simple que funciona, que algo complejo que no funciona.** 🎓✨ 