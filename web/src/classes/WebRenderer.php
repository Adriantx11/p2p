<?php

class WebRenderer {
    private $config;
    private $templateDir;
    
    public function __construct($config) {
        $this->config = $config;
        $this->templateDir = __DIR__ . '/../../templates/';
    }
    
    public function render($template, $data = []) {
        $templateFile = $this->templateDir . $template . '.php';
        
        if (!file_exists($templateFile)) {
            throw new Exception("Template not found: {$template}");
        }
        
        // Extraer variables para usar en la plantilla
        extract($data);
        
        // Capturar el contenido de la plantilla
        ob_start();
        include $templateFile;
        $content = ob_get_clean();
        
        // Si no es una página de error o login, usar el layout principal
        if (!in_array($template, ['login', 'register', 'error', '404'])) {
            $this->renderWithLayout($content, $data);
        } else {
            echo $content;
        }
    }
    
    private function renderWithLayout($content, $data) {
        $layoutFile = $this->templateDir . 'layout.php';
        
        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }
        
        // Extraer variables para el layout
        extract($data);
        $pageContent = $content;
        
        include $layoutFile;
    }
    
    public function renderPartial($partial, $data = []) {
        $partialFile = $this->templateDir . 'partials/' . $partial . '.php';
        
        if (!file_exists($partialFile)) {
            return '';
        }
        
        extract($data);
        
        ob_start();
        include $partialFile;
        return ob_get_clean();
    }
    
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    public function formatDate($date, $format = 'Y-m-d H:i:s') {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        return $date->format($format);
    }
    
    public function formatTime($timestamp) {
        $now = time();
        $diff = $now - $timestamp;
        
        if ($diff < 60) {
            return 'hace ' . $diff . ' segundos';
        } elseif ($diff < 3600) {
            return 'hace ' . floor($diff / 60) . ' minutos';
        } elseif ($diff < 86400) {
            return 'hace ' . floor($diff / 3600) . ' horas';
        } else {
            return 'hace ' . floor($diff / 86400) . ' días';
        }
    }
    
    public function asset($path) {
        return '/web/public/assets/' . ltrim($path, '/');
    }
    
    public function url($path = '') {
        return $this->config['app']['url'] . '/' . ltrim($path, '/');
    }
}