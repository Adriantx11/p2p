<?php

class SecurityMiddleware {
    private $config;
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    public function apply() {
        // Aplicar headers de seguridad
        $this->setSecurityHeaders();
        
        // Verificar CSRF en requests POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCSRFToken();
        }
    }
    
    private function setSecurityHeaders() {
        // Prevenir clickjacking
        header('X-Frame-Options: DENY');
        
        // Prevenir MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Activar XSS protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy básico
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
        
        // Solo HTTPS en producción
        if (!$this->config['app']['debug']) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
    
    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    private function verifyCSRFToken() {
        $tokenName = $this->config['security']['csrf_token_name'];
        $submittedToken = $_POST[$tokenName] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        
        if (!$submittedToken || !$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            http_response_code(403);
            die(json_encode(['error' => 'CSRF token mismatch']));
        }
    }
    
    public function checkRateLimit() {
        $identifier = $this->getRateLimitIdentifier();
        $config = $this->config['security']['rate_limit'];
        
        // Verificar límite por minuto
        $db = new WebDB($this->config);
        return $db->checkRateLimit($identifier, $config['requests_per_minute'], 60);
    }
    
    private function getRateLimitIdentifier() {
        // Usar IP + User Agent como identificador
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        return hash('sha256', $ip . '|' . $userAgent);
    }
    
    public function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        // Limpiar HTML y caracteres especiales
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public function validateCreditCard($cardNumber) {
        // Remover espacios y guiones
        $cardNumber = preg_replace('/[\s\-]/', '', $cardNumber);
        
        // Verificar que solo contenga números
        if (!ctype_digit($cardNumber)) {
            return false;
        }
        
        // Verificar longitud (13-19 dígitos)
        $length = strlen($cardNumber);
        if ($length < 13 || $length > 19) {
            return false;
        }
        
        // Algoritmo de Luhn
        return $this->luhnCheck($cardNumber);
    }
    
    private function luhnCheck($cardNumber) {
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
    
    public function logSecurityEvent($event, $details = []) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'details' => $details
        ];
        
        $logFile = dirname($this->config['logging']['file']) . '/security.log';
        file_put_contents($logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    public function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public function isValidBIN($bin) {
        return ctype_digit($bin) && strlen($bin) >= 6 && strlen($bin) <= 8;
    }
    
    public function detectSuspiciousActivity($userId = null) {
        // Implementar detección de actividad sospechosa
        $suspiciousPatterns = [
            'too_many_requests' => $this->checkRequestFrequency(),
            'invalid_user_agent' => $this->checkUserAgent(),
            'suspicious_ip' => $this->checkIPReputation()
        ];
        
        foreach ($suspiciousPatterns as $pattern => $detected) {
            if ($detected) {
                $this->logSecurityEvent('suspicious_activity', [
                    'pattern' => $pattern,
                    'user_id' => $userId
                ]);
                return true;
            }
        }
        
        return false;
    }
    
    private function checkRequestFrequency() {
        // Verificar si hay demasiadas requests en poco tiempo
        return false; // Implementar lógica específica
    }
    
    private function checkUserAgent() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Detectar user agents sospechosos
        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function checkIPReputation() {
        // Implementar verificación de reputación de IP
        return false; // Placeholder
    }
}