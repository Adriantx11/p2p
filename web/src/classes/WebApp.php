<?php

class WebApp {
    public $config;
    public $db;
    public $auth;
    public $api;
    public $renderer;
    public $security;
    
    public function __construct($config) {
        $this->config = $config;
        
        // Inicializar componentes
        $this->db = new WebDB($config);
        $this->auth = new WebAuth($config, $this->db);
        $this->api = new WebAPI($config, $this->db, $this->auth);
        $this->renderer = new WebRenderer($config);
        $this->security = new SecurityMiddleware($config);
        
        // Configurar zona horaria
        date_default_timezone_set($config['app']['timezone']);
        
        // Aplicar middleware de seguridad
        $this->security->apply();
    }
    
    public function renderPage($page, $data = []) {
        // Verificar rate limiting
        if (!$this->security->checkRateLimit()) {
            http_response_code(429);
            $this->renderer->render('error', [
                'title' => 'Too Many Requests',
                'message' => 'Por favor, espera un momento antes de hacer otra solicitud.'
            ]);
            return;
        }
        
        // Datos comunes para todas las páginas
        $commonData = [
            'app_name' => $this->config['app']['name'],
            'app_version' => $this->config['app']['version'],
            'user' => $this->auth->getCurrentUser(),
            'csrf_token' => $this->security->generateCSRFToken(),
            'current_page' => $page
        ];
        
        $data = array_merge($commonData, $data);
        
        $this->renderer->render($page, $data);
    }
    
    public function handleError($code, $message = null) {
        http_response_code($code);
        
        $errorMessages = [
            404 => 'Página no encontrada',
            403 => 'Acceso denegado',
            500 => 'Error interno del servidor',
            429 => 'Demasiadas solicitudes'
        ];
        
        $this->renderPage('error', [
            'title' => 'Error ' . $code,
            'message' => $message ?: ($errorMessages[$code] ?? 'Error desconocido')
        ]);
    }
    
    public function log($level, $message, $context = []) {
        if (!$this->config['logging']['enabled']) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        file_put_contents($this->config['logging']['file'], $logEntry, FILE_APPEND | LOCK_EX);
    }
}