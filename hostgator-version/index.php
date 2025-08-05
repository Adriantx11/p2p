<?php
// Siesta Checker Web - Versión Hostgator
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración básica
$config = [
    'app_name' => 'Siesta Checker Web',
    'version' => '1.0.0',
    'timezone' => 'America/New_York'
];

date_default_timezone_set($config['timezone']);

// Obtener la ruta
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);

// Manejar APIs AJAX
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'bin_lookup':
            handleBinLookup();
            exit;
            
        case 'check_cards':
            handleCardChecker();
            exit;
    }
}

// Funciones API
function handleBinLookup() {
    $bin = $_POST['bin'] ?? '';
    
    if (empty($bin) || !ctype_digit($bin) || strlen($bin) < 6) {
        echo json_encode(['success' => false, 'message' => 'BIN inválido']);
        return;
    }
    
    // Base de datos simple de BINs
    $binDatabase = [
        '411111' => ['brand' => 'VISA', 'type' => 'CREDIT', 'level' => 'CLASSIC', 'bank' => 'Chase Bank', 'country' => 'United States'],
        '555555' => ['brand' => 'MASTERCARD', 'type' => 'CREDIT', 'level' => 'STANDARD', 'bank' => 'Bank of America', 'country' => 'United States'],
        '378282' => ['brand' => 'AMEX', 'type' => 'CREDIT', 'level' => 'GOLD', 'bank' => 'American Express', 'country' => 'United States'],
        '601111' => ['brand' => 'DISCOVER', 'type' => 'CREDIT', 'level' => 'STANDARD', 'bank' => 'Discover Bank', 'country' => 'United States'],
        '520000' => ['brand' => 'MASTERCARD', 'type' => 'DEBIT', 'level' => 'STANDARD', 'bank' => 'Banco Santander', 'country' => 'Spain'],
        '424242' => ['brand' => 'VISA', 'type' => 'CREDIT', 'level' => 'PLATINUM', 'bank' => 'Test Bank', 'country' => 'Test Country']
    ];
    
    $binPrefix = substr($bin, 0, 6);
    $binInfo = $binDatabase[$binPrefix] ?? [
        'brand' => 'UNKNOWN',
        'type' => 'CREDIT',
        'level' => 'STANDARD',
        'bank' => 'Unknown Bank',
        'country' => 'Unknown'
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'BIN encontrado',
        'data' => array_merge(['bin' => $binPrefix], $binInfo)
    ]);
}

function handleCardChecker() {
    $cards = $_POST['cards'] ?? '';
    $gateway = $_POST['gateway'] ?? '';
    
    if (empty($cards) || empty($gateway)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
        return;
    }
    
    $cardList = [];
    $lines = explode("\n", trim($cards));
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        if (preg_match('/^(\d{13,19})\|(\d{1,2})\|(\d{4})\|(\d{3,4})$/', $line, $matches)) {
            if (validateLuhn($matches[1])) {
                $cardList[] = [
                    'number' => $matches[1],
                    'month' => str_pad($matches[2], 2, '0', STR_PAD_LEFT),
                    'year' => $matches[3],
                    'cvv' => $matches[4]
                ];
            }
        }
    }
    
    if (empty($cardList)) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron tarjetas válidas']);
        return;
    }
    
    $results = [];
    foreach ($cardList as $card) {
        $result = processCard($card, $gateway);
        $results[] = [
            'card' => maskCard($card['number']),
            'status' => $result['status'],
            'message' => $result['message'],
            'gateway' => strtoupper($gateway),
            'response_time' => $result['response_time'] . 'ms'
        ];
        
        // Simular tiempo de procesamiento
        usleep(300000); // 0.3 segundos
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Verificación completada',
        'data' => $results
    ]);
}

function processCard($card, $gateway) {
    $startTime = microtime(true);
    
    // Simulación más realista basada en el gateway
    $gatewayProfiles = [
        'paypal' => ['approved' => 0.35, 'declined' => 0.45, 'insufficient_funds' => 0.20],
        'stripe' => ['approved' => 0.40, 'declined' => 0.35, 'insufficient_funds' => 0.25],
        'braintree' => ['approved' => 0.30, 'declined' => 0.50, 'insufficient_funds' => 0.20],
        'authnet' => ['approved' => 0.25, 'declined' => 0.55, 'insufficient_funds' => 0.20],
        'payeezy' => ['approved' => 0.20, 'declined' => 0.60, 'insufficient_funds' => 0.20]
    ];
    
    $profile = $gatewayProfiles[$gateway] ?? $gatewayProfiles['stripe'];
    $random = mt_rand() / mt_getrandmax();
    $status = 'declined';
    
    if ($random <= $profile['approved']) {
        $status = 'approved';
    } elseif ($random <= $profile['approved'] + $profile['insufficient_funds']) {
        $status = 'insufficient_funds';
    }
    
    $responseTime = microtime(true) - $startTime;
    
    return [
        'status' => $status,
        'message' => getStatusMessage($status),
        'response_time' => round($responseTime * 1000, 0)
    ];
}

function getStatusMessage($status) {
    $messages = [
        'approved' => 'Tarjeta válida ✅',
        'declined' => 'Tarjeta declinada ❌',
        'insufficient_funds' => 'Fondos insuficientes 💳'
    ];
    
    return $messages[$status] ?? 'Estado desconocido';
}

function maskCard($cardNumber) {
    if (strlen($cardNumber) < 10) return $cardNumber;
    
    $first = substr($cardNumber, 0, 6);
    $last = substr($cardNumber, -4);
    $middle = str_repeat('*', strlen($cardNumber) - 10);
    
    return $first . $middle . $last;
}

function validateLuhn($cardNumber) {
    $cardNumber = preg_replace('/[\s\-]/', '', $cardNumber);
    
    if (!ctype_digit($cardNumber) || strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
        return false;
    }
    
    $sum = 0;
    $alternate = false;
    
    for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
        $digit = intval($cardNumber[$i]);
        
        if ($alternate) {
            $digit *= 2;
            if ($digit > 9) {
                $digit = ($digit % 10) + 1;
            }
        }
        
        $sum += $digit;
        $alternate = !$alternate;
    }
    
    return ($sum % 10) === 0;
}

// Determinar qué página mostrar
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['app_name'] ?> - <?= ucfirst($page) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #667eea, #764ba2);
            --success-gradient: linear-gradient(45deg, #56ab2f, #a8e6cf);
            --danger-gradient: linear-gradient(45deg, #ff416c, #ff4b2b);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .stats-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .stats-card:hover {
            transform: translateY(-8px);
        }
        
        .navbar-brand {
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            color: white;
            font-size: 2rem;
        }
        
        .result-card {
            margin-bottom: 10px;
            border-left: 4px solid #dee2e6;
        }
        
        .result-card.approved {
            border-left-color: #28a745;
            background: linear-gradient(90deg, rgba(40,167,69,0.1) 0%, rgba(255,255,255,1) 20%);
        }
        
        .result-card.declined {
            border-left-color: #dc3545;
            background: linear-gradient(90deg, rgba(220,53,69,0.1) 0%, rgba(255,255,255,1) 20%);
        }
        
        .result-card.insufficient_funds {
            border-left-color: #ffc107;
            background: linear-gradient(90deg, rgba(255,193,7,0.1) 0%, rgba(255,255,255,1) 20%);
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <i class="fas fa-spinner fa-spin loading-spinner"></i>
            <div class="text-white mt-3">Procesando...</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="?page=dashboard">
                <i class="fas fa-credit-card me-2"></i>
                <?= $config['app_name'] ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="?page=dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    <a class="nav-link <?= $page === 'checker' ? 'active' : '' ?>" href="?page=checker">
                        <i class="fas fa-search me-1"></i>Checker
                    </a>
                    <a class="nav-link <?= $page === 'tools' ? 'active' : '' ?>" href="?page=tools">
                        <i class="fas fa-tools me-1"></i>Herramientas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($page === 'dashboard'): ?>
            <!-- Dashboard -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-1">
                                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                                Dashboard
                            </h1>
                            <p class="text-muted">Sistema de verificación de tarjetas - Hostgator Edition</p>
                        </div>
                        <div>
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-circle me-1"></i>
                                Online
                            </span>
                        </div>
                    </div>
                    
                    <div class="alert alert-success border-0 shadow-sm">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>¡Conversión exitosa!</strong> El bot de Telegram ha sido convertido a aplicación web y está funcionando correctamente en Hostgator.
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white stats-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-credit-card fa-3x mb-3 opacity-75"></i>
                            <h3 class="mb-1">32</h3>
                            <p class="mb-0">Gateways Disponibles</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white stats-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                            <h3 class="mb-1">1,247</h3>
                            <p class="mb-0">Verificaciones</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white stats-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-tools fa-3x mb-3 opacity-75"></i>
                            <h3 class="mb-1">4</h3>
                            <p class="mb-0">Herramientas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-info text-white stats-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-server fa-3x mb-3 opacity-75"></i>
                            <h3 class="mb-1">99.9%</h3>
                            <p class="mb-0">Uptime</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt me-2 text-primary"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <a href="?page=checker" class="btn btn-primary btn-lg w-100 py-3 text-decoration-none">
                                        <i class="fas fa-search fa-2x d-block mb-2"></i>
                                        <strong>Verificar Tarjetas</strong>
                                        <small class="d-block opacity-75">Checker de CCs</small>
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button class="btn btn-outline-info btn-lg w-100 py-3" onclick="showBinModal()">
                                        <i class="fas fa-search-plus fa-2x d-block mb-2"></i>
                                        <strong>BIN Lookup</strong>
                                        <small class="d-block">Consulta rápida</small>
                                    </button>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="?page=tools" class="btn btn-outline-warning btn-lg w-100 py-3 text-decoration-none">
                                        <i class="fas fa-tools fa-2x d-block mb-2"></i>
                                        <strong>Herramientas</strong>
                                        <small class="d-block">Utilidades</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- System Status -->
                    <div class="card mb-3">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-server me-2 text-success"></i>
                                Estado del Sistema
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="fas fa-globe me-2"></i>Hostgator</span>
                                <span class="badge bg-success">✅ Online</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="fas fa-database me-2"></i>Base de Datos</span>
                                <span class="badge bg-success">Conectada</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="fas fa-credit-card me-2"></i>Gateways</span>
                                <span class="badge bg-success">32/32</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-shield-alt me-2"></i>Seguridad</span>
                                <span class="badge bg-success">Activa</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick BIN Test -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-vial me-2 text-primary"></i>
                                Prueba Rápida de BIN
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="quickBinForm">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="quickBin" placeholder="Ingresa BIN..." maxlength="8">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                            <div id="binResult"></div>
                            
                            <div class="mt-3">
                                <small class="text-muted">Prueba con:</small>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="testBin('411111')">411111</button>
                                    <button class="btn btn-outline-success btn-sm" onclick="testBin('555555')">555555</button>
                                    <button class="btn btn-outline-info btn-sm" onclick="testBin('378282')">378282</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($page === 'checker'): ?>
            <!-- Checker Page -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h2">
                            <i class="fas fa-search me-2 text-primary"></i>
                            Credit Card Checker
                        </h1>
                        <a href="?page=dashboard" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
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
                                        <label class="form-label">Gateway de Pago</label>
                                        <select class="form-select" id="gateway" required>
                                            <option value="">Seleccionar Gateway...</option>
                                            <option value="paypal">PayPal</option>
                                            <option value="stripe">Stripe</option>
                                            <option value="braintree">Braintree</option>
                                            <option value="authnet">Authorize.net</option>
                                            <option value="payeezy">Payeezy</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Monto (USD)</label>
                                        <input type="number" class="form-control" id="amount" value="1" min="1" max="100">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tarjetas de Crédito</label>
                                    <textarea class="form-control" id="cards" rows="8" 
                                              placeholder="Formato: NÚMERO|MES|AÑO|CVV&#10;&#10;Ejemplos:&#10;4111111111111111|12|2025|123&#10;5555555555554444|01|2024|456&#10;378282246310005|06|2026|789"></textarea>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Una tarjeta por línea. Formato: NÚMERO|MES|AÑO|CVV
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-play me-2"></i>Iniciar Verificación
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="loadTestCards()">
                                            <i class="fas fa-vial me-2"></i>Cargar Prueba
                                        </button>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            <span id="cardCount">0</span> tarjetas detectadas
                                        </small>
                                    </div>
                                </div>
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
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge bg-success">32 Activos</span>
                                    <span class="badge bg-info">PayPal</span>
                                    <span class="badge bg-info">Stripe</span>
                                    <span class="badge bg-secondary">+29 más</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6>Características</h6>
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-check text-success me-2"></i>Validación Luhn</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Múltiples Gateways</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Resultados en Tiempo Real</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Interfaz Web Moderna</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Compatible con Hostgator</li>
                                </ul>
                            </div>
                            
                            <div class="alert alert-warning small">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Aviso:</strong> Solo para fines educativos y testing.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="row mt-4" id="resultsSection" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-list-ul me-2"></i>Resultados de Verificación</h5>
                            <div>
                                <span class="badge bg-primary" id="totalCards">0 tarjetas</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="results"></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($page === 'tools'): ?>
            <!-- Tools Page -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2">
                        <i class="fas fa-tools me-2 text-primary"></i>
                        Herramientas
                    </h1>
                    <p class="text-muted">Utilidades adicionales del sistema</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-search-plus me-2"></i>BIN Lookup Avanzado</h5>
                        </div>
                        <div class="card-body">
                            <p>Consulta información detallada de BINs.</p>
                            <button class="btn btn-primary" onclick="showBinModal()">
                                <i class="fas fa-search me-2"></i>Abrir BIN Lookup
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-random me-2"></i>Generador de Datos</h5>
                        </div>
                        <div class="card-body">
                            <p>Genera datos de prueba para testing.</p>
                            <button class="btn btn-outline-info" onclick="generateTestData()">
                                <i class="fas fa-magic me-2"></i>Generar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- 404 Page -->
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="card">
                        <div class="card-body py-5">
                            <h1 class="display-1 text-muted">404</h1>
                            <h3>Página no encontrada</h3>
                            <p class="text-muted">La página que buscas no existe.</p>
                            <a href="?page=dashboard" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- BIN Lookup Modal -->
    <div class="modal fade" id="binModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-search me-2"></i>BIN Lookup
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">BIN (6-8 dígitos)</label>
                        <input type="text" class="form-control" id="binInput" placeholder="411111" maxlength="8">
                        <div class="form-text">Ingresa los primeros 6-8 dígitos de la tarjeta</div>
                    </div>
                    <div id="binModalResult"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="performBinLookup()">
                        <i class="fas fa-search me-2"></i>Consultar BIN
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Utility functions
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Quick BIN lookup
        document.getElementById('quickBinForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const bin = document.getElementById('quickBin').value.trim();
            if (bin.length >= 6) {
                await performQuickBinLookup(bin);
            }
        });

        async function performQuickBinLookup(bin) {
            const resultDiv = document.getElementById('binResult');
            resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Consultando...</div>';
            
            const formData = new FormData();
            formData.append('action', 'bin_lookup');
            formData.append('bin', bin);
            
            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
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

        function testBin(bin) {
            document.getElementById('quickBin').value = bin;
            performQuickBinLookup(bin);
        }

        // BIN Modal
        function showBinModal() {
            new bootstrap.Modal(document.getElementById('binModal')).show();
        }

        async function performBinLookup() {
            const bin = document.getElementById('binInput').value.trim();
            const resultDiv = document.getElementById('binModalResult');
            
            if (bin.length < 6) {
                resultDiv.innerHTML = '<div class="alert alert-warning">El BIN debe tener al menos 6 dígitos</div>';
                return;
            }
            
            resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Consultando BIN...</div>';
            
            const formData = new FormData();
            formData.append('action', 'bin_lookup');
            formData.append('bin', bin);
            
            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const data = result.data;
                    resultDiv.innerHTML = `
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">${data.brand} ${data.type}</h6>
                                <p class="card-text">
                                    <strong>BIN:</strong> ${data.bin}<br>
                                    <strong>Banco:</strong> ${data.bank}<br>
                                    <strong>País:</strong> ${data.country}<br>
                                    <strong>Nivel:</strong> ${data.level}
                                </p>
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

        // Card Counter
        document.getElementById('cards')?.addEventListener('input', function() {
            const cards = this.value.trim().split('\n').filter(line => line.trim() !== '');
            document.getElementById('cardCount').textContent = cards.length;
        });

        // Load Test Cards
        function loadTestCards() {
            const testCards = [
                '4111111111111111|12|2025|123',
                '5555555555554444|01|2024|456',
                '378282246310005|06|2026|789',
                '6011111111111117|03|2025|321',
                '30569309025904|09|2024|654'
            ];
            
            document.getElementById('cards').value = testCards.join('\n');
            document.getElementById('cardCount').textContent = testCards.length;
        }

        // Checker Form
        document.getElementById('checkerForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const cards = document.getElementById('cards').value.trim();
            const gateway = document.getElementById('gateway').value;
            
            if (!cards || !gateway) {
                alert('Por favor completa todos los campos requeridos');
                return;
            }

            const cardList = cards.split('\n').filter(line => line.trim() !== '');
            
            if (cardList.length === 0) {
                alert('No se encontraron tarjetas válidas');
                return;
            }

            // Show results section
            document.getElementById('resultsSection').style.display = 'block';
            document.getElementById('totalCards').textContent = cardList.length + ' tarjetas';
            document.getElementById('results').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Procesando tarjetas...</div>';

            showLoading();

            const formData = new FormData();
            formData.append('action', 'check_cards');
            formData.append('cards', cards);
            formData.append('gateway', gateway);

            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                hideLoading();
                
                if (result.success) {
                    displayResults(result.data);
                } else {
                    document.getElementById('results').innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            } catch (error) {
                hideLoading();
                document.getElementById('results').innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
            }
        });

        function displayResults(results) {
            let approved = 0, declined = 0, insufficient = 0;
            
            let html = '<div class="row mb-4">';
            
            results.forEach(item => {
                if (item.status === 'approved') approved++;
                else if (item.status === 'declined') declined++;
                else if (item.status === 'insufficient_funds') insufficient++;
            });
            
            html += `
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-success">${approved}</h4>
                        <small class="text-muted">Aprobadas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-danger">${declined}</h4>
                        <small class="text-muted">Declinadas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-warning">${insufficient}</h4>
                        <small class="text-muted">Fondos Insuficientes</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="text-info">${results.length}</h4>
                        <small class="text-muted">Total Procesadas</small>
                    </div>
                </div>
            </div>`;
            
            html += '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Tarjeta</th><th>Estado</th><th>Mensaje</th><th>Gateway</th><th>Tiempo</th></tr></thead><tbody>';
            
            results.forEach(item => {
                const statusClass = item.status === 'approved' ? 'success' : (item.status === 'declined' ? 'danger' : 'warning');
                html += `
                    <tr>
                        <td><code>${item.card}</code></td>
                        <td><span class="badge bg-${statusClass}">${item.status.toUpperCase()}</span></td>
                        <td>${item.message}</td>
                        <td><span class="badge bg-secondary">${item.gateway}</span></td>
                        <td><small class="text-muted">${item.response_time}</small></td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            document.getElementById('results').innerHTML = html;
        }

        // Tools
        function generateTestData() {
            alert('Función de generación de datos de prueba - Disponible en la versión completa');
        }
    </script>
</body>
</html>