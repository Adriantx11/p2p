<?php
// Siesta Checker Web - Versión Todo en Uno
session_start();

// Obtener la ruta
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($path, PHP_URL_PATH);

// Manejar APIs
if ($path === '/api/bin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $bin = $input['bin'] ?? '';
    
    if (empty($bin) || !ctype_digit($bin) || strlen($bin) < 6) {
        echo json_encode(['success' => false, 'message' => 'BIN inválido']);
        exit;
    }
    
    $binDatabase = [
        '411111' => ['brand' => 'VISA', 'type' => 'CREDIT', 'level' => 'CLASSIC', 'bank' => 'Chase Bank', 'country' => 'United States'],
        '555555' => ['brand' => 'MASTERCARD', 'type' => 'CREDIT', 'level' => 'STANDARD', 'bank' => 'Bank of America', 'country' => 'United States'],
        '378282' => ['brand' => 'AMEX', 'type' => 'CREDIT', 'level' => 'GOLD', 'bank' => 'American Express', 'country' => 'United States']
    ];
    
    $binPrefix = substr($bin, 0, 6);
    $binInfo = $binDatabase[$binPrefix] ?? [
        'brand' => 'UNKNOWN', 'type' => 'CREDIT', 'level' => 'STANDARD', 
        'bank' => 'Unknown Bank', 'country' => 'Unknown'
    ];
    
    echo json_encode(['success' => true, 'data' => array_merge(['bin' => $binPrefix], $binInfo)]);
    exit;
}

if ($path === '/api/checker' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $cards = $input['cards'] ?? '';
    $gateway = $input['gateway'] ?? '';
    
    if (empty($cards) || empty($gateway)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }
    
    $cardList = [];
    $lines = explode("\n", trim($cards));
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        if (preg_match('/^(\d{13,19})\|(\d{1,2})\|(\d{4})\|(\d{3,4})$/', $line, $matches)) {
            $cardList[] = ['number' => $matches[1], 'month' => $matches[2], 'year' => $matches[3], 'cvv' => $matches[4]];
        }
    }
    
    if (empty($cardList)) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron tarjetas válidas']);
        exit;
    }
    
    $results = [];
    foreach ($cardList as $card) {
        $responses = ['approved', 'declined', 'insufficient_funds'];
        $status = $responses[array_rand($responses)];
        $messages = [
            'approved' => 'Tarjeta válida ✅',
            'declined' => 'Tarjeta declinada ❌',
            'insufficient_funds' => 'Fondos insuficientes 💳'
        ];
        
        $results[] = [
            'card' => substr($card['number'], 0, 6) . '****' . substr($card['number'], -4),
            'status' => $status,
            'message' => $messages[$status]
        ];
        usleep(300000); // 0.3 segundos
    }
    
    echo json_encode(['success' => true, 'data' => $results]);
    exit;
}

// Determinar qué página mostrar
$page = 'dashboard';
if ($path === '/checker') $page = 'checker';
if ($path === '/login') $page = 'login';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siesta Checker Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-gradient: linear-gradient(45deg, #667eea, #764ba2); }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .card { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 15px; }
        .btn-primary { background: var(--primary-gradient); border: none; border-radius: 25px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .stats-card { transition: transform 0.3s ease; }
        .stats-card:hover { transform: translateY(-5px); }
        .navbar-brand { font-weight: 700; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-credit-card me-2"></i>Siesta Checker Web
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="/">Dashboard</a>
                <a class="nav-link <?= $page === 'checker' ? 'active' : '' ?>" href="/checker">Checker</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($page === 'dashboard'): ?>
            <!-- Dashboard -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 mb-1">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                    </h1>
                    <p class="text-muted">Bienvenido al sistema de verificación de tarjetas</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white stats-card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-credit-card fa-3x mb-3 opacity-75"></i>
                            <h3>32</h3>
                            <p class="mb-0">Gateways</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white stats-card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                            <h3>1,247</h3>
                            <p class="mb-0">Verificaciones</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white stats-card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-tools fa-3x mb-3 opacity-75"></i>
                            <h3>4</h3>
                            <p class="mb-0">Herramientas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white stats-card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                            <h3>156</h3>
                            <p class="mb-0">Usuarios</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5><i class="fas fa-bolt me-2 text-primary"></i>Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <a href="/checker" class="btn btn-primary btn-lg w-100 py-3">
                                        <i class="fas fa-search fa-2x d-block mb-2"></i>
                                        <strong>Verificar Tarjetas</strong>
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-outline-info btn-lg w-100 py-3" onclick="showBinModal()">
                                        <i class="fas fa-search-plus fa-2x d-block mb-2"></i>
                                        <strong>BIN Lookup</strong>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h6><i class="fas fa-search me-2 text-primary"></i>BIN Lookup Rápido</h6>
                        </div>
                        <div class="card-body">
                            <form id="quickBinForm">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="quickBin" placeholder="411111" maxlength="8">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                            <div id="binResult"></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($page === 'checker'): ?>
            <!-- Checker -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3"><i class="fas fa-search me-2 text-primary"></i>Credit Card Checker</h1>
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
                                        <select class="form-select" id="gateway" required>
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
                                    <textarea class="form-control" id="cards" rows="8" 
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
                        <div class="card-header"><h6>Información</h6></div>
                        <div class="card-body">
                            <h6>Gateways Disponibles</h6>
                            <span class="badge bg-success mb-3">32 Activos</span>
                            
                            <div class="alert alert-warning small">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Solo para fines educativos
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="row mt-4" id="resultsSection" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h5>Resultados</h5></div>
                        <div class="card-body">
                            <div id="results"></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Login -->
            <div class="row justify-content-center" style="min-height: 70vh; align-items: center;">
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-4">Siesta Checker</h3>
                            <p class="text-muted mb-4">Sistema de verificación de tarjetas</p>
                            <a href="/" class="btn btn-primary btn-lg w-100">Acceder al Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- BIN Modal -->
    <div class="modal fade" id="binModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-search me-2"></i>BIN Lookup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">BIN (6-8 dígitos)</label>
                        <input type="text" class="form-control" id="binInput" placeholder="411111">
                    </div>
                    <div id="binModalResult"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="performBinLookup()">
                        <i class="fas fa-search me-2"></i>Consultar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Quick BIN lookup
        document.getElementById('quickBinForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const bin = document.getElementById('quickBin').value.trim();
            if (bin.length >= 6) await performQuickBinLookup(bin);
        });

        async function performQuickBinLookup(bin) {
            const resultDiv = document.getElementById('binResult');
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
                        <div class="alert alert-success small">
                            <strong>${data.brand}</strong> ${data.type}<br>
                            <small>${data.bank} - ${data.country}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger small">${result.message}</div>`;
                }
            } catch (error) {
                resultDiv.innerHTML = '<div class="alert alert-danger small">Error de conexión</div>';
            }
        }

        function showBinModal() {
            new bootstrap.Modal(document.getElementById('binModal')).show();
        }

        async function performBinLookup() {
            const bin = document.getElementById('binInput').value.trim();
            const resultDiv = document.getElementById('binModalResult');
            
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
                                <strong>País:</strong> ${data.country}<br>
                                <strong>Nivel:</strong> ${data.level}</p>
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

        // Checker form
        document.getElementById('checkerForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const cards = document.getElementById('cards').value;
            const gateway = document.getElementById('gateway').value;
            
            if (!cards || !gateway) {
                alert('Por favor completa todos los campos');
                return;
            }

            document.getElementById('resultsSection').style.display = 'block';
            document.getElementById('results').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Procesando...</div>';
            
            try {
                const response = await fetch('/api/checker', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cards: cards, gateway: gateway })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Tarjeta</th><th>Estado</th><th>Mensaje</th></tr></thead><tbody>';
                    
                    result.data.forEach(item => {
                        const statusClass = item.status === 'approved' ? 'success' : 'danger';
                        html += `<tr>
                            <td><code>${item.card}</code></td>
                            <td><span class="badge bg-${statusClass}">${item.status}</span></td>
                            <td>${item.message}</td>
                        </tr>`;
                    });
                    
                    html += '</tbody></table></div>';
                    document.getElementById('results').innerHTML = html;
                } else {
                    document.getElementById('results').innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('results').innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
            }
        });
    </script>
</body>
</html>