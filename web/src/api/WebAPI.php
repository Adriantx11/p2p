<?php

class WebAPI {
    private $config;
    private $db;
    private $auth;
    private $security;
    
    public function __construct($config, $db, $auth) {
        $this->config = $config;
        $this->db = $db;
        $this->auth = $auth;
        $this->security = new SecurityMiddleware($config);
        
        // Configurar headers para API
        header('Content-Type: application/json');
    }
    
    public function handleLogin() {
        try {
            $email = $this->security->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $this->jsonResponse(false, 'Email y contraseña son requeridos.');
                return;
            }
            
            $result = $this->auth->login($email, $password);
            $this->jsonResponse($result['success'], $result['message'], $result['user'] ?? null);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error interno del servidor.');
            $this->logError('Login error', $e);
        }
    }
    
    public function handleRegister() {
        try {
            $email = $this->security->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $username = $this->security->sanitizeInput($_POST['username'] ?? '');
            
            if (empty($email) || empty($password)) {
                $this->jsonResponse(false, 'Email y contraseña son requeridos.');
                return;
            }
            
            $result = $this->auth->register($email, $password, $username);
            $this->jsonResponse($result['success'], $result['message']);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error interno del servidor.');
            $this->logError('Register error', $e);
        }
    }
    
    public function handleChecker() {
        try {
            if (!$this->auth->isLoggedIn()) {
                $this->jsonResponse(false, 'No autorizado.');
                return;
            }
            
            $cards = $this->security->sanitizeInput($_POST['cards'] ?? '');
            $gateway = $this->security->sanitizeInput($_POST['gateway'] ?? '');
            
            if (empty($cards) || empty($gateway)) {
                $this->jsonResponse(false, 'Tarjetas y gateway son requeridos.');
                return;
            }
            
            // Procesar tarjetas
            $cardList = $this->parseCards($cards);
            if (empty($cardList)) {
                $this->jsonResponse(false, 'No se encontraron tarjetas válidas.');
                return;
            }
            
            // Verificar límite de tarjetas por usuario
            if (count($cardList) > 50) {
                $this->jsonResponse(false, 'Máximo 50 tarjetas por verificación.');
                return;
            }
            
            // Procesar verificación
            $results = $this->processCards($cardList, $gateway);
            
            // Registrar actividad
            $user = $this->auth->getCurrentUser();
            $this->db->logActivity($user['id'], 'card_check', [
                'gateway' => $gateway,
                'cards_count' => count($cardList)
            ]);
            
            $this->jsonResponse(true, 'Verificación completada.', $results);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error en la verificación.');
            $this->logError('Checker error', $e);
        }
    }
    
    public function handleBinLookup() {
        try {
            if (!$this->auth->isLoggedIn()) {
                $this->jsonResponse(false, 'No autorizado.');
                return;
            }
            
            $bin = $this->security->sanitizeInput($_POST['bin'] ?? '');
            
            if (empty($bin) || !$this->security->isValidBIN($bin)) {
                $this->jsonResponse(false, 'BIN inválido.');
                return;
            }
            
            // Usar las herramientas existentes del bot
            require_once '../../Tools/bin.php';
            
            $binInfo = $this->getBinInfo($bin);
            
            // Registrar actividad
            $user = $this->auth->getCurrentUser();
            $this->db->logActivity($user['id'], 'bin_lookup', ['bin' => $bin]);
            
            $this->jsonResponse(true, 'BIN lookup completado.', $binInfo);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error en BIN lookup.');
            $this->logError('BIN lookup error', $e);
        }
    }
    
    public function handleAddressGen() {
        try {
            if (!$this->auth->isLoggedIn()) {
                $this->jsonResponse(false, 'No autorizado.');
                return;
            }
            
            $country = $this->security->sanitizeInput($_POST['country'] ?? 'US');
            $state = $this->security->sanitizeInput($_POST['state'] ?? '');
            
            // Usar las herramientas existentes del bot
            $fake = new Fake();
            $address = $fake->generateAddress($country, $state);
            
            // Registrar actividad
            $user = $this->auth->getCurrentUser();
            $this->db->logActivity($user['id'], 'address_gen', ['country' => $country]);
            
            $this->jsonResponse(true, 'Dirección generada.', $address);
            
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Error generando dirección.');
            $this->logError('Address gen error', $e);
        }
    }
    
    private function parseCards($cardsText) {
        $cards = [];
        $lines = explode("\n", trim($cardsText));
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Formatos soportados: 4111111111111111|12|2025|123
            if (preg_match('/^(\d{13,19})\|(\d{1,2})\|(\d{4})\|(\d{3,4})$/', $line, $matches)) {
                $card = [
                    'number' => $matches[1],
                    'month' => str_pad($matches[2], 2, '0', STR_PAD_LEFT),
                    'year' => $matches[3],
                    'cvv' => $matches[4]
                ];
                
                // Validar tarjeta
                if ($this->security->validateCreditCard($card['number'])) {
                    $cards[] = $card;
                }
            }
        }
        
        return $cards;
    }
    
    private function processCards($cards, $gateway) {
        $results = [];
        
        // Verificar que el gateway existe
        $gatewayFile = "../../Gates/{$gateway}.php";
        if (!file_exists($gatewayFile)) {
            throw new Exception("Gateway no encontrado: {$gateway}");
        }
        
        // Cargar clases necesarias
        $curlx = new CurlX();
        $fake = new Fake();
        
        foreach ($cards as $index => $card) {
            try {
                // Simular el procesamiento del gateway
                $result = $this->processCard($card, $gateway, $curlx, $fake);
                $results[] = [
                    'card' => $this->maskCard($card['number']),
                    'status' => $result['status'],
                    'message' => $result['message'],
                    'response_time' => $result['response_time'] ?? 0
                ];
                
                // Límite de rate para evitar sobrecargar
                if ($index > 0 && $index % 5 === 0) {
                    sleep(1);
                }
                
            } catch (Exception $e) {
                $results[] = [
                    'card' => $this->maskCard($card['number']),
                    'status' => 'error',
                    'message' => 'Error procesando tarjeta',
                    'response_time' => 0
                ];
            }
        }
        
        return $results;
    }
    
    private function processCard($card, $gateway, $curlx, $fake) {
        $startTime = microtime(true);
        
        // Generar datos fake para la verificación
        $fakeData = $fake->generateFakeData();
        
        // Preparar datos de la tarjeta
        $cardData = [
            'cc' => $card['number'],
            'mes' => $card['month'],
            'ano' => $card['year'],
            'cvv' => $card['cvv'],
            'email' => $fakeData['email'],
            'name' => $fakeData['name'],
            'address' => $fakeData['address'],
            'city' => $fakeData['city'],
            'state' => $fakeData['state'],
            'zip' => $fakeData['zip'],
            'phone' => $fakeData['phone']
        ];
        
        // Simular respuesta del gateway (en producción esto ejecutaría el gateway real)
        $responseTime = microtime(true) - $startTime;
        
        // Por ahora retornamos una respuesta simulada
        $responses = ['approved', 'declined', 'insufficient_funds', 'invalid_card'];
        $status = $responses[array_rand($responses)];
        
        return [
            'status' => $status,
            'message' => $this->getStatusMessage($status),
            'response_time' => round($responseTime * 1000, 2)
        ];
    }
    
    private function getStatusMessage($status) {
        $messages = [
            'approved' => 'Tarjeta válida ✅',
            'declined' => 'Tarjeta declinada ❌',
            'insufficient_funds' => 'Fondos insuficientes 💳',
            'invalid_card' => 'Tarjeta inválida ⚠️'
        ];
        
        return $messages[$status] ?? 'Estado desconocido';
    }
    
    private function maskCard($cardNumber) {
        return substr($cardNumber, 0, 6) . str_repeat('*', strlen($cardNumber) - 10) . substr($cardNumber, -4);
    }
    
    private function getBinInfo($bin) {
        // Implementar BIN lookup usando APIs externas o base de datos local
        // Por ahora retornamos datos simulados
        return [
            'bin' => $bin,
            'brand' => 'VISA',
            'type' => 'CREDIT',
            'level' => 'CLASSIC',
            'bank' => 'Example Bank',
            'country' => 'United States',
            'country_code' => 'US'
        ];
    }
    
    private function jsonResponse($success, $message, $data = null) {
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response);
    }
    
    private function logError($context, $exception) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'context' => $context,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        $logFile = dirname($this->config['logging']['file']) . '/api_errors.log';
        file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}