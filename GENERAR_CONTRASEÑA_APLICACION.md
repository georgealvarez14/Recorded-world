# 🔐 Guía Visual: Generar Contraseña de Aplicación Gmail

## 🎯 **Paso a Paso con Imágenes**

### **Paso 1: Ir a tu cuenta de Google**
1. Abre tu navegador
2. Ve a: https://myaccount.google.com/
3. Inicia sesión con tu cuenta `recordedworld941@gmail.com`

### **Paso 2: Activar Verificación en Dos Pasos**
1. En el menú izquierdo, haz clic en **"Seguridad"**
2. Busca **"Verificación en dos pasos"**
3. Si no está activada, haz clic en **"Comenzar"**
4. Sigue los pasos para activarla

### **Paso 3: Generar Contraseña de Aplicación**
1. En **"Seguridad"**, busca **"Contraseñas de aplicación"**
2. Haz clic en **"Contraseñas de aplicación"**
3. Selecciona **"Otra (nombre personalizado)"**
4. Escribe: **"Sistema de Eventos"**
5. Haz clic en **"Generar"**

### **Paso 4: Copiar la Contraseña**
- Aparecerá una contraseña de **16 caracteres**
- Ejemplo: `abcd efgh ijkl mnop`
- **Copia esta contraseña completa**

### **Paso 5: Actualizar el Archivo**
1. Abre: `src/config/email_config.php`
2. Busca la línea:
   ```php
   define('SMTP_PASSWORD', 'TU_CONTRASEÑA_DE_APLICACION');
   ```
3. Reemplaza `TU_CONTRASEÑA_DE_APLICACION` con tu contraseña real
4. Ejemplo:
   ```php
   define('SMTP_PASSWORD', 'abcd efgh ijkl mnop');
   ```

---

## ⚠️ **Importante:**

### **❌ NO uses:**
- Tu contraseña normal de Gmail
- Contraseñas temporales
- Contraseñas de otras aplicaciones

### **✅ SÍ usa:**
- La contraseña de aplicación específica
- Los 16 caracteres exactos
- Sin espacios adicionales

---

## 🧪 **Probar Después de Configurar:**

```bash
php test_email_phpmailer.php
```

### **Resultado Esperado:**
```
✅ Email enviado exitosamente a: recordedworld941@gmail.com
📧 Revisa tu bandeja de entrada
```

---

## 🔧 **Si Tienes Problemas:**

### **Error: "Verificación en dos pasos no activada"**
- Activa primero la verificación en dos pasos
- Luego genera la contraseña de aplicación

### **Error: "Contraseña incorrecta"**
- Verifica que copiaste los 16 caracteres exactos
- No agregues espacios adicionales
- Usa la contraseña más reciente

### **Error: "Cuenta bloqueada"**
- Espera unos minutos
- Verifica que tu cuenta no esté bloqueada
- Revisa tu email por notificaciones de seguridad

---

## 🎯 **Para la Exposición:**

Una vez configurado, podrás mostrar:
- ✅ **Configuración segura** con Gmail
- ✅ **Email real** funcionando
- ✅ **Notificación inmediata** al admin
- ✅ **Sistema híbrido** completo

¡Tu sistema de contacto estará completamente funcional! 🚀 