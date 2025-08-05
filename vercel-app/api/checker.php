<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$cards = $input['cards'] ?? '';
$gateway = $input['gateway'] ?? '';

// Validate input
if (empty($cards) || empty($gateway)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tarjetas y gateway son requeridos.'
    ]);
    exit;
}

// Parse cards
$cardList = parseCards($cards);
if (empty($cardList)) {
    echo json_encode([
        'success' => false,
        'message' => 'No se encontraron tarjetas válidas.'
    ]);
    exit;
}

// Limit cards for serverless
if (count($cardList) > 10) {
    echo json_encode([
        'success' => false,
        'message' => 'Máximo 10 tarjetas en versión serverless.'
    ]);
    exit;
}

// Process cards
$results = [];
foreach ($cardList as $card) {
    $result = processCard($card, $gateway);
    $results[] = [
        'card' => maskCard($card['number']),
        'status' => $result['status'],
        'message' => $result['message'],
        'response_time' => $result['response_time']
    ];
    
    // Small delay to prevent overwhelming
    usleep(100000); // 0.1 seconds
}

echo json_encode([
    'success' => true,
    'message' => 'Verificación completada.',
    'data' => $results
]);

function parseCards($cardsText) {
    $cards = [];
    $lines = explode("\n", trim($cardsText));
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Format: 4111111111111111|12|2025|123
        if (preg_match('/^(\d{13,19})\|(\d{1,2})\|(\d{4})\|(\d{3,4})$/', $line, $matches)) {
            $card = [
                'number' => $matches[1],
                'month' => str_pad($matches[2], 2, '0', STR_PAD_LEFT),
                'year' => $matches[3],
                'cvv' => $matches[4]
            ];
            
            // Validate card with Luhn algorithm
            if (validateCreditCard($card['number'])) {
                $cards[] = $card;
            }
        }
    }
    
    return $cards;
}

function processCard($card, $gateway) {
    $startTime = microtime(true);
    
    // Simulate gateway processing
    $responses = ['approved', 'declined', 'insufficient_funds', 'invalid_card'];
    $weights = [0.3, 0.4, 0.2, 0.1]; // Probability weights
    
    $random = mt_rand() / mt_getrandmax();
    $cumulative = 0;
    $status = 'declined';
    
    foreach ($responses as $i => $response) {
        $cumulative += $weights[$i];
        if ($random <= $cumulative) {
            $status = $response;
            break;
        }
    }
    
    // Simulate processing time
    usleep(mt_rand(500000, 2000000)); // 0.5-2 seconds
    
    $responseTime = microtime(true) - $startTime;
    
    return [
        'status' => $status,
        'message' => getStatusMessage($status),
        'response_time' => round($responseTime * 1000, 2)
    ];
}

function getStatusMessage($status) {
    $messages = [
        'approved' => 'Tarjeta válida ✅',
        'declined' => 'Tarjeta declinada ❌',
        'insufficient_funds' => 'Fondos insuficientes 💳',
        'invalid_card' => 'Tarjeta inválida ⚠️'
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

function validateCreditCard($cardNumber) {
    // Remove spaces and dashes
    $cardNumber = preg_replace('/[\s\-]/', '', $cardNumber);
    
    // Check if only digits
    if (!ctype_digit($cardNumber)) {
        return false;
    }
    
    // Check length
    if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
        return false;
    }
    
    // Luhn algorithm
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
?>