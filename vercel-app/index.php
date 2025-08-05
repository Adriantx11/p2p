<?php
// Vercel Serverless Entry Point
header('Content-Type: text/html; charset=utf-8');

// Error handling for production
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Simple session handling for serverless
if (!isset($_COOKIE['session_token'])) {
    $session_token = bin2hex(random_bytes(16));
    setcookie('session_token', $session_token, time() + 3600, '/', '', true, true);
} else {
    $session_token = $_COOKIE['session_token'];
}

// Get the requested path
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);

// Simple routing
switch ($path) {
    case '/':
    case '/dashboard':
        renderDashboard();
        break;
        
    case '/login':
        renderLogin();
        break;
        
    case '/checker':
        renderChecker();
        break;
        
    default:
        render404();
        break;
}

function renderDashboard() {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Siesta Checker - Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-gradient: linear-gradient(45deg, #667eea, #764ba2);
            }
            body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
            .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .btn-primary { background: var(--primary-gradient); border: none; }
            .navbar-brand { background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/">
                    <i class="fas fa-credit-card me-2"></i>Siesta Checker
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/checker">
                        <i class="fas fa-search me-1"></i>Checker
                    </a>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <h1 class="h3 mb-4">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                        Dashboard
                    </h1>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-credit-card fa-2x mb-2"></i>
                            <h4>32</h4>
                            <small>Gateways Disponibles</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4>1,247</h4>
                            <small>Verificaciones</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-tools fa-2x mb-2"></i>
                            <h4>4</h4>
                            <small>Herramientas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4>Online</h4>
                            <small>Estado</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <a href="/checker" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-search me-2"></i>Verificar Tarjetas
                            </a>
                            <button class="btn btn-outline-info w-100" onclick="showBinLookup()">
                                <i class="fas fa-search-plus me-2"></i>BIN Lookup
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle me-2"></i>Información</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Version:</strong> 2.0 Serverless</p>
                            <p><strong>Platform:</strong> Vercel</p>
                            <p><strong>Runtime:</strong> PHP 8.2</p>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Sistema operativo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BIN Lookup Modal -->
        <div class="modal fade" id="binModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">BIN Lookup</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">BIN (6-8 dígitos)</label>
                            <input type="text" class="form-control" id="binInput" placeholder="411111">
                        </div>
                        <div id="binResult"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="performBinLookup()">Consultar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function showBinLookup() {
                new bootstrap.Modal(document.getElementById('binModal')).show();
            }
            
            async function performBinLookup() {
                const bin = document.getElementById('binInput').value.trim();
                const resultDiv = document.getElementById('binResult');
                
                if (bin.length < 6) {
                    resultDiv.innerHTML = '<div class="alert alert-warning">BIN debe tener al menos 6 dígitos</div>';
                    return;
                }
                
                resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Consultando...</div>';
                
                try {
                    const response = await fetch('/api/bin', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ bin: bin })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        const data = result.data;
                        resultDiv.innerHTML = `
                            <div class="card">
                                <div class="card-body">
                                    <h6>${data.brand} ${data.type}</h6>
                                    <p><strong>Banco:</strong> ${data.bank}<br>
                                    <strong>País:</strong> ${data.country}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        resultDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                    }
                } catch (error) {
                    resultDiv.innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
                }
            }
        </script>
    </body>
    </html>
    <?php
}

function renderLogin() {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Siesta Checker</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
            }
            .login-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card login-card border-0">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-4">
                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                Siesta Checker
                            </h3>
                            <p class="text-muted mb-4">Versión Serverless en Vercel</p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Esta es una versión demo. En producción aquí iría el sistema de autenticación completo.
                            </div>
                            
                            <a href="/dashboard" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Acceder al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function renderChecker() {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checker - Siesta Checker</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
            .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <i class="fas fa-credit-card me-2"></i>Siesta Checker
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <h1 class="h3 mb-4">
                        <i class="fas fa-search me-2 text-primary"></i>
                        Credit Card Checker
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-credit-card me-2"></i>Verificar Tarjetas</h5>
                        </div>
                        <div class="card-body">
                            <form id="checkerForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Gateway</label>
                                        <select class="form-select" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="paypal">PayPal</option>
                                            <option value="stripe">Stripe</option>
                                            <option value="braintree">Braintree</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Monto</label>
                                        <input type="number" class="form-control" value="1" min="1" max="100">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tarjetas</label>
                                    <textarea class="form-control" rows="8" 
                                              placeholder="4111111111111111|12|2025|123&#10;5555555555554444|01|2024|456"></textarea>
                                    <div class="form-text">Formato: NÚMERO|MES|AÑO|CVV</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-play me-2"></i>Verificar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="fas fa-info-circle me-2"></i>Información</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Gateways Disponibles</h6>
                                <span class="badge bg-success">32 Activos</span>
                            </div>
                            
                            <div class="alert alert-warning small">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Solo para fines educativos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.getElementById('checkerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('En la versión completa, aquí se procesarían las tarjetas usando la API serverless.');
            });
        </script>
    </body>
    </html>
    <?php
}

function render404() {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 - Página no encontrada</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light d-flex align-items-center min-vh-100">
        <div class="container text-center">
            <h1 class="display-1">404</h1>
            <p class="fs-3">Página no encontrada</p>
            <a href="/" class="btn btn-primary">Volver al inicio</a>
        </div>
    </body>
    </html>
    <?php
}
?>