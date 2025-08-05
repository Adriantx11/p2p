<?php

// Autoloader para la aplicación web
spl_autoload_register(function ($className) {
    $paths = [
        // Clases web nuevas
        __DIR__ . '/classes/' . $className . '.php',
        __DIR__ . '/auth/' . $className . '.php',
        __DIR__ . '/api/' . $className . '.php',
        __DIR__ . '/middleware/' . $className . '.php',
        
        // Clases originales del bot (adaptadas)
        __DIR__ . '/../../Class/Class_' . $className . '.php',
        __DIR__ . '/../../Class/' . $className . '.php',
        
        // Funciones y herramientas
        __DIR__ . '/../../Functions/' . $className . '.php',
        __DIR__ . '/../../Tools/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Cargar archivos de funciones y configuración necesarios
require_once __DIR__ . '/../../require.php';

// Cargar clases web principales
require_once __DIR__ . '/classes/WebApp.php';
require_once __DIR__ . '/auth/WebAuth.php';
require_once __DIR__ . '/api/WebAPI.php';
require_once __DIR__ . '/classes/WebDB.php';
require_once __DIR__ . '/classes/WebRenderer.php';
require_once __DIR__ . '/middleware/SecurityMiddleware.php';