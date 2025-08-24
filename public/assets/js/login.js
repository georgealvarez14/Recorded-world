document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const rememberMe = document.getElementById('rememberMe').checked;
        
        // Validaciones básicas
        if (!username || !password) {
            showAlert('Por favor, completa todos los campos', 'error');
            return;
        }
        
        // Mostrar indicador de carga
        const loginButton = document.querySelector('.login-button');
        const originalText = loginButton.textContent;
        loginButton.textContent = 'INICIANDO SESIÓN...';
        loginButton.disabled = true;
        
        // Datos para enviar al servidor
        const formData = new FormData();
        formData.append('usuario', username);
        formData.append('contrasena', password);
        formData.append('rememberMe', rememberMe);
        
        // Enviar petición AJAX
        fetch('php/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Login exitoso. Redirigiendo...', 'success');
                
                // Redireccionar después de 1.5 segundos
                setTimeout(() => {
                    window.location.href = data.redirect || 'dashboard.php';
                }, 1500);
            } else {
                showAlert(data.message || 'Credenciales incorrectas', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error de conexión. Por favor, intenta de nuevo.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            loginButton.textContent = originalText;
            loginButton.disabled = false;
        });
    });
    
    // Función para mostrar alertas
    function showAlert(message, type) {
        // Remover alerta existente
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Crear nueva alerta
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        
        // Insertar antes del formulario
        const loginBox = document.querySelector('.login-box');
        loginBox.insertBefore(alert, loginBox.firstChild);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Efectos adicionales para mejorar UX
    const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});