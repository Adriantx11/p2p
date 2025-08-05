<?php

class WebAuth {
    private $config;
    private $db;
    
    public function __construct($config, $db) {
        $this->config = $config;
        $this->db = $db;
        
        // Configurar sesión
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.name', $config['auth']['session_name']);
            ini_set('session.gc_maxlifetime', $config['auth']['session_lifetime']);
            session_start();
        }
    }
    
    public function login($email, $password) {
        // Verificar intentos de login
        if ($this->isLockedOut($email)) {
            return [
                'success' => false,
                'message' => 'Cuenta bloqueada temporalmente por múltiples intentos fallidos.'
            ];
        }
        
        // Buscar usuario
        $user = $this->db->getUserByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->recordFailedAttempt($email);
            return [
                'success' => false,
                'message' => 'Email o contraseña incorrectos.'
            ];
        }
        
        // Verificar si el usuario está activo
        if (!$user['is_active']) {
            return [
                'success' => false,
                'message' => 'Cuenta desactivada. Contacta al administrador.'
            ];
        }
        
        // Login exitoso
        $this->clearFailedAttempts($email);
        $this->createSession($user);
        $this->db->updateLastLogin($user['id']);
        
        return [
            'success' => true,
            'message' => 'Login exitoso.',
            'user' => $this->sanitizeUser($user)
        ];
    }
    
    public function register($email, $password, $username = null) {
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Email inválido.'
            ];
        }
        
        // Validar contraseña
        if (strlen($password) < $this->config['auth']['password_min_length']) {
            return [
                'success' => false,
                'message' => 'La contraseña debe tener al menos ' . $this->config['auth']['password_min_length'] . ' caracteres.'
            ];
        }
        
        // Verificar si el email ya existe
        if ($this->db->getUserByEmail($email)) {
            return [
                'success' => false,
                'message' => 'El email ya está registrado.'
            ];
        }
        
        // Crear usuario
        $userData = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_ARGON2ID),
            'username' => $username ?: explode('@', $email)[0],
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $userId = $this->db->createUser($userData);
        if (!$userId) {
            return [
                'success' => false,
                'message' => 'Error al crear la cuenta.'
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Cuenta creada exitosamente.',
            'user_id' => $userId
        ];
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->db->logActivity($_SESSION['user_id'], 'logout');
        }
        
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $user = $this->db->getUserById($_SESSION['user_id']);
        return $user ? $this->sanitizeUser($user) : null;
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function requireAdmin() {
        $user = $this->getCurrentUser();
        if (!$user || !$user['is_admin']) {
            http_response_code(403);
            exit('Acceso denegado');
        }
    }
    
    private function createSession($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['login_time'] = time();
        
        $this->db->logActivity($user['id'], 'login');
    }
    
    private function sanitizeUser($user) {
        unset($user['password']);
        return $user;
    }
    
    private function isLockedOut($email) {
        $attempts = $this->db->getFailedLoginAttempts($email);
        if ($attempts >= $this->config['auth']['max_login_attempts']) {
            $lastAttempt = $this->db->getLastFailedAttempt($email);
            if ($lastAttempt && (time() - strtotime($lastAttempt)) < $this->config['auth']['lockout_time']) {
                return true;
            }
        }
        return false;
    }
    
    private function recordFailedAttempt($email) {
        $this->db->recordFailedLoginAttempt($email);
    }
    
    private function clearFailedAttempts($email) {
        $this->db->clearFailedLoginAttempts($email);
    }
}