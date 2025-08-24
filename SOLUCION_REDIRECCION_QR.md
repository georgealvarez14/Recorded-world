# Solución para Redirección del Sistema QR

## Problema Identificado

El usuario reportó que cuando intentaba realizar acciones en la sección de QR (Sistema de Códigos QR), el sistema lo redirigía al dashboard en lugar de mantenerlo en la página de QR. Esto ocurría porque:

1. **Sesión Expirada**: Cuando la sesión del usuario expiraba, el sistema redirigía a la página de login
2. **Redirección Incorrecta**: Después del login exitoso, el usuario era redirigido al dashboard por defecto (`index.php?accion=inicio`) en lugar de regresar a la página QR que estaba intentando acceder

## Solución Implementada

### 1. Función Helper para Redirecciones

Se agregó una función helper al inicio de `public/index.php`:

```php
// Función helper para manejar redirecciones con guardado de URL
function redirectToLoginWithReturn() {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: index.php?accion=login');
    exit;
}
```

### 2. Modificación del Procesamiento de Login

Se modificó el caso `procesar_login` para verificar si existe una URL de redirección guardada:

```php
// Verificar si hay una redirección guardada
if (isset($_SESSION['redirect_after_login'])) {
    $redirect_url = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']); // Limpiar la variable
    header('Location: ' . $redirect_url);
    exit;
}
```

### 3. Actualización de Todas las Rutas QR

Se actualizaron todas las rutas relacionadas con QR para usar la nueva función helper:

#### Rutas Actualizadas:
- `generar_qr`
- `ver_qr`
- `descargar_qr`
- `generar_qr_persona`
- `eliminar_qr_persona`
- `ver_qr_persona`
- `generar_qr_evento`
- `generar_qr_estudiantes`
- `generar_qr_eventos`
- `generar_qr_masivo`
- `eliminar_qr_masivo`
- `generar_qr_masivo_grado`
- `generar_qr_masivo_tipo`
- `generar_qr_masivo_todos`
- `generar_qr_masivo_eventos`
- `eliminar_qr_masivo_grado`
- `eliminar_qr_masivo_tipo`
- `eliminar_qr_masivo_eventos`
- `eliminar_qr_masivo_todos`

#### Ejemplo de Cambio:
```php
// ANTES:
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
    header('Location: index.php?accion=login');
    exit;
}

// DESPUÉS:
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'ADM') {
    redirectToLoginWithReturn();
}
```

## Cómo Funciona

1. **Usuario Accede a QR**: El usuario navega a `index.php?accion=generar_qr`
2. **Sesión Expirada**: Si la sesión ha expirado, se guarda la URL actual en `$_SESSION['redirect_after_login']`
3. **Redirección a Login**: El usuario es redirigido a la página de login
4. **Login Exitoso**: Después del login, el sistema verifica si hay una URL de redirección guardada
5. **Retorno a QR**: Si existe, el usuario es redirigido de vuelta a la página QR original
6. **Limpieza**: La variable de sesión se elimina para evitar redirecciones no deseadas

## Beneficios

- **Experiencia de Usuario Mejorada**: Los usuarios regresan automáticamente a donde estaban
- **Consistencia**: Todas las rutas QR manejan las redirecciones de la misma manera
- **Mantenibilidad**: Una sola función maneja todas las redirecciones
- **Seguridad**: Solo se aplica a rutas que requieren autenticación de administrador

## Archivos Modificados

- `public/index.php`: Agregada función helper y modificado procesamiento de login

## Pruebas Recomendadas

1. Acceder a la sección QR con sesión válida
2. Cerrar sesión y intentar acceder a la sección QR
3. Hacer login y verificar que regresa a la página QR
4. Probar con diferentes rutas QR (generar, eliminar, ver)
5. Verificar que funciona con sesiones expiradas

## Notas Técnicas

- La función usa `$_SERVER['REQUEST_URI']` para capturar la URL completa
- Se limpia la variable de sesión después de usarla para evitar redirecciones no deseadas
- Solo se aplica a rutas que requieren autenticación de administrador (`ADM`)
- La solución es compatible con el sistema de rutas existente 