<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar configuración y clases
require_once '../src/config/web_config.php';
require_once '../src/autoload.php';

// Inicializar aplicación web
$app = new WebApp($webConfig);

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace('/web/public', '', $path);

// Enrutamiento básico
switch ($path) {
    case '/':
    case '/dashboard':
        if (!$app->auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $app->renderPage('dashboard');
        break;
        
    case '/login':
        if ($app->auth->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        $app->renderPage('login');
        break;
        
    case '/register':
        if ($app->auth->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        $app->renderPage('register');
        break;
        
    case '/logout':
        $app->auth->logout();
        header('Location: /login');
        exit;
        
    case '/api/auth/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $app->api->handleLogin();
        }
        break;
        
    case '/api/auth/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $app->api->handleRegister();
        }
        break;
        
    case '/api/checker':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $app->auth->isLoggedIn()) {
            $app->api->handleChecker();
        }
        break;
        
    case '/api/tools/bin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $app->auth->isLoggedIn()) {
            $app->api->handleBinLookup();
        }
        break;
        
    case '/api/tools/gen':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $app->auth->isLoggedIn()) {
            $app->api->handleAddressGen();
        }
        break;
        
    case '/checker':
        if (!$app->auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $app->renderPage('checker');
        break;
        
    case '/tools':
        if (!$app->auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $app->renderPage('tools');
        break;
        
    case '/profile':
        if (!$app->auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $app->renderPage('profile');
        break;
        
    default:
        http_response_code(404);
        $app->renderPage('404');
        break;
}