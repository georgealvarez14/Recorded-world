# 📧 Configuración de Email con Gmail - Guía Completa

## 🎯 **Paso a Paso para Configurar Email Real**

### **Paso 1: Preparar tu cuenta de Gmail**

#### **1.1 Habilitar verificación en dos pasos:**
1. Ve a tu cuenta de Google
2. Seguridad → Verificación en dos pasos
3. Activa la verificación en dos pasos

#### **1.2 Generar contraseña de aplicación:**
1. Ve a tu cuenta de Google
2. Seguridad → Contraseñas de aplicación
3. Selecciona "Otra" y ponle nombre "Sistema de Eventos"
4. **Copia la contraseña generada** (16 caracteres)

---

### **Paso 2: Configurar el archivo de email**

#### **2.1 Editar `src/config/email_config.php`:**

```php
// Cambiar estas líneas:
define('SMTP_USERNAME', 'tu-email@gmail.com');   // Tu email real
define('SMTP_PASSWORD', 'tu-contraseña-de-aplicacion');  // La contraseña de 16 caracteres
define('ADMIN_EMAIL', 'admin@institucion.edu.co');  // Email donde recibirás los mensajes
define('EMAIL_SIMULATION', false);  // Cambiar a false para email real
```

#### **2.2 Ejemplo de configuración:**

```php
// Configuración real (ejemplo)
define('SMTP_USERNAME', 'mi-institucion@gmail.com');
define('SMTP_PASSWORD', 'abcd efgh ijkl mnop');  // Contraseña de aplicación
define('ADMIN_EMAIL', 'director@institucion.edu.co');
define('EMAIL_SIMULATION', false);
```

---

### **Paso 3: Instalar PHPMailer (Opcional pero recomendado)**

#### **3.1 Usando Composer:**
```bash
composer require phpmailer/phpmailer
```

#### **3.2 Manualmente:**
1. Descarga PHPMailer desde GitHub
2. Copia la carpeta `src` a tu proyecto
3. Incluye en `ContactoController.php`:

```php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
```

---

### **Paso 4: Probar la configuración**

#### **4.1 Crear archivo de prueba:**
```php
<?php
// test_email.php
require_once 'src/config/email_config.php';
require_once 'src/config/database.php';

// Probar envío
$to = ADMIN_EMAIL;
$subject = "Prueba de email - Sistema de Eventos";
$message = "Este es un email de prueba del sistema de eventos.";

$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: Sistema de Eventos <' . SMTP_FROM_EMAIL . '>',
    'Reply-To: noreply@institucion.edu.co',
    'X-Mailer: PHP/' . phpversion()
);

$sent = mail($to, $subject, $message, implode("\r\n", $headers));

if ($sent) {
    echo "✅ Email enviado correctamente a: $to";
} else {
    echo "❌ Error enviando email";
}
?>
```

#### **4.2 Ejecutar la prueba:**
```bash
php test_email.php
```

---

## 🔧 **Configuración Avanzada**

### **Opción 2: Servidor propio**

Si tienes un servidor propio, configura así:

```php
// En email_config.php
define('SMTP_HOST', 'tu-servidor.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'noreply@tu-servidor.com');
define('SMTP_PASSWORD', 'tu-contraseña-del-servidor');
```

### **Opción 3: Servicios externos**

#### **SendGrid:**
```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'apikey');
define('SMTP_PASSWORD', 'tu-api-key-de-sendgrid');
```

#### **Mailgun:**
```php
define('SMTP_HOST', 'smtp.mailgun.org');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'postmaster@tu-dominio.com');
define('SMTP_PASSWORD', 'tu-password-de-mailgun');
```

---

## 🧪 **Pruebas y Verificación**

### **1. Verificar configuración:**
```php
<?php
// verify_email_config.php
require_once 'src/config/email_config.php';

$status = getEmailStatus();
echo "Estado del sistema de email:\n";
echo "Habilitado: " . ($status['enabled'] ? 'Sí' : 'No') . "\n";
echo "Simulación: " . ($status['simulation'] ? 'Sí' : 'No') . "\n";
echo "Configurado: " . ($status['configured'] ? 'Sí' : 'No') . "\n";
echo "Errores: " . implode(', ', $status['errors']) . "\n";
?>
```

### **2. Probar desde el formulario:**
1. Ve a `index.php?action=contacto`
2. Completa el formulario
3. Envía el mensaje
4. Verifica que llegue el email

### **3. Verificar logs:**
```bash
# Ver logs de email
tail -f logs/contacto.log
```

---

## ⚠️ **Problemas Comunes y Soluciones**

### **Error: "SMTP connect() failed"**
**Solución:**
- Verificar que la verificación en dos pasos esté activada
- Usar contraseña de aplicación, no la contraseña normal
- Verificar que el puerto 587 esté abierto

### **Error: "Authentication failed"**
**Solución:**
- Verificar que el email y contraseña sean correctos
- Asegurarse de usar contraseña de aplicación
- Verificar que la cuenta no esté bloqueada

### **Error: "Connection timeout"**
**Solución:**
- Verificar conexión a internet
- Verificar firewall del servidor
- Probar con puerto 465 (SSL) en lugar de 587 (TLS)

### **Emails van a spam:**
**Solución:**
- Configurar SPF y DKIM en el dominio
- Usar email institucional en lugar de Gmail
- Configurar headers correctos

---

## 📋 **Checklist de Configuración**

### **Antes de empezar:**
- [ ] Tener cuenta de Gmail
- [ ] Habilitar verificación en dos pasos
- [ ] Generar contraseña de aplicación
- [ ] Tener acceso al servidor

### **Configuración:**
- [ ] Editar `email_config.php`
- [ ] Cambiar credenciales
- [ ] Deshabilitar simulación
- [ ] Instalar PHPMailer (opcional)

### **Pruebas:**
- [ ] Ejecutar `test_email.php`
- [ ] Probar formulario de contacto
- [ ] Verificar logs
- [ ] Confirmar recepción de emails

---

## 🎯 **Para la Exposición**

### **Puedes mostrar:**
1. **Configuración del email** en el código
2. **Prueba de envío** en tiempo real
3. **Email recibido** en la bandeja de entrada
4. **Logs del sistema** para auditoría

### **Mensajes clave:**
- "El sistema puede enviar emails reales"
- "Configuración segura con Gmail"
- "Notificación inmediata al administrador"
- "Respaldo en base de datos si falla el email"

---

## 🏆 **Resultado Final**

Con esta configuración tendrás:
- ✅ **Emails reales** funcionando
- ✅ **Notificación inmediata** al admin
- ✅ **Configuración segura** con Gmail
- ✅ **Sistema híbrido** completo
- ✅ **Logs de auditoría** detallados

¡Tu sistema de contacto estará completamente funcional! 🚀 