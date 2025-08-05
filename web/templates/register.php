<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Siesta Checker Web</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px 20px 0 0;
        }
        .btn-register {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
    
    <meta name="csrf-token" content="<?= $csrf_token ?>">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card register-card border-0">
                    <div class="card-header register-header text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Crear Cuenta
                        </h3>
                        <p class="mb-0 opacity-75">Únete a Siesta Checker Web</p>
                    </div>
                    
                    <div class="card-body p-5">
                        <div id="alert-container"></div>
                        
                        <form id="registerForm">
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nombre de Usuario
                                </label>
                                <input type="text" class="form-control" id="username" name="username" maxlength="50">
                                <div class="form-text">Opcional - se generará automáticamente si se deja vacío</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <div class="form-text">Mínimo 8 caracteres</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                                </label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="8">
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> 
                                    y la <a href="#" class="text-decoration-none">política de privacidad</a>
                                </label>
                            </div>
                            
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-register text-white">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Crear Cuenta
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes cuenta?</p>
                            <a href="/login" class="text-decoration-none">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Iniciar sesión
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent text-center py-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Conexión segura SSL
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password confirmation validation
        document.getElementById('password_confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            
            if (confirm && password !== confirm) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Register form handler
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Validate passwords match
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            
            if (password !== confirm) {
                showAlert('danger', 'Las contraseñas no coinciden.');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...';
            
            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message + ' Redirigiendo al login...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showAlert('danger', result.message);
                }
            } catch (error) {
                showAlert('danger', 'Error de conexión. Inténtalo de nuevo.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Crear Cuenta';
            }
        });
        
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>