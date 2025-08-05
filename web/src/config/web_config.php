<?php

// Cargar configuración original del bot
require_once '../../Config/Config.php';

// Configuración específica para la aplicación web
$webConfig = [
    // Configuración de base de datos (heredada del bot)
    'database' => [
        'type' => $config['db']['type'] ?? 'sqlite',
        'path' => '../../' . ($config['db']['path'] ?? 'database/siesta.db'),
        'host' => $config['db']['host'] ?? null,
        'port' => $config['db']['port'] ?? null,
        'name' => $config['db']['name'] ?? null,
        'user' => $config['db']['user'] ?? null,
        'pass' => $config['db']['pass'] ?? null
    ],
    
    // Configuración de la aplicación web
    'app' => [
        'name' => 'Siesta Checker Web',
        'version' => '1.0.0',
        'timezone' => $config['timeZone'] ?? 'UTC',
        'debug' => true,
        'url' => 'http://localhost/web/public'
    ],
    
    // Configuración de autenticación
    'auth' => [
        'session_name' => 'siesta_session',
        'session_lifetime' => 3600 * 24 * 7, // 7 días
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_time' => 900 // 15 minutos
    ],
    
    // Configuración de seguridad
    'security' => [
        'csrf_token_name' => 'csrf_token',
        'rate_limit' => [
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000
        ],
        'anti_spam_timer' => $config['anti_spam_timer'] ?? 30
    ],
    
    // Configuración de idiomas (heredada del bot)
    'languages' => [
        'default' => 'es',
        'available' => ['es', 'en', 'fr', 'it', 'de', 'pt']
    ],
    
    // Configuración de gateways (heredada del bot)
    'gateways' => [
        'enabled' => true,
        'timeout' => 30,
        'max_concurrent' => 5
    ],
    
    // Configuración de herramientas
    'tools' => [
        'bin_lookup' => true,
        'address_generator' => true,
        'site_checker' => true,
        'card_validator' => true
    ],
    
    // Configuración de logs
    'logging' => [
        'enabled' => true,
        'level' => 'INFO',
        'file' => '../../logs/web_app.log',
        'max_size' => 10 * 1024 * 1024 // 10MB
    ]
];

// Crear directorio de logs si no existe
$logDir = dirname($webConfig['logging']['file']);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}