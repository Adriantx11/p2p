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
$bin = $input['bin'] ?? '';

// Validate BIN
if (empty($bin) || !ctype_digit($bin) || strlen($bin) < 6) {
    echo json_encode([
        'success' => false,
        'message' => 'BIN inválido. Debe contener al menos 6 dígitos.'
    ]);
    exit;
}

// Simple BIN database (in production, use external API or database)
$binDatabase = [
    '411111' => [
        'brand' => 'VISA',
        'type' => 'CREDIT',
        'level' => 'CLASSIC',
        'bank' => 'Chase Bank',
        'country' => 'United States',
        'country_code' => 'US'
    ],
    '555555' => [
        'brand' => 'MASTERCARD',
        'type' => 'CREDIT',
        'level' => 'STANDARD',
        'bank' => 'Bank of America',
        'country' => 'United States',
        'country_code' => 'US'
    ],
    '378282' => [
        'brand' => 'AMERICAN EXPRESS',
        'type' => 'CREDIT',
        'level' => 'GOLD',
        'bank' => 'American Express',
        'country' => 'United States',
        'country_code' => 'US'
    ],
    '601111' => [
        'brand' => 'DISCOVER',
        'type' => 'CREDIT',
        'level' => 'STANDARD',
        'bank' => 'Discover Bank',
        'country' => 'United States',
        'country_code' => 'US'
    ]
];

// Look up BIN (check first 6 digits)
$binPrefix = substr($bin, 0, 6);
$binInfo = null;

// Try exact match first
if (isset($binDatabase[$binPrefix])) {
    $binInfo = $binDatabase[$binPrefix];
} else {
    // Try partial matches
    foreach ($binDatabase as $key => $data) {
        if (strpos($binPrefix, $key) === 0) {
            $binInfo = $data;
            break;
        }
    }
}

if ($binInfo) {
    echo json_encode([
        'success' => true,
        'message' => 'BIN encontrado',
        'data' => array_merge(['bin' => $binPrefix], $binInfo)
    ]);
} else {
    // Use external API or return generic info
    echo json_encode([
        'success' => true,
        'message' => 'BIN procesado',
        'data' => [
            'bin' => $binPrefix,
            'brand' => 'UNKNOWN',
            'type' => 'CREDIT',
            'level' => 'STANDARD',
            'bank' => 'Unknown Bank',
            'country' => 'Unknown',
            'country_code' => 'XX'
        ]
    ]);
}
?>