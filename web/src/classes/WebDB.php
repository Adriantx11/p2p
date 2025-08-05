<?php

class WebDB extends DB {
    
    public function __construct($config) {
        // Llamar al constructor padre con la configuración adaptada
        $botConfig = [
            'db' => $config['database']
        ];
        parent::__construct($botConfig, null);
        
        // Crear tablas específicas para la aplicación web
        $this->initWebTables();
    }
    
    private function initWebTables() {
        // Tabla de usuarios web
        $this->query("CREATE TABLE IF NOT EXISTS web_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            username VARCHAR(100),
            is_active BOOLEAN DEFAULT 1,
            is_admin BOOLEAN DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME
        )");
        
        // Tabla de intentos de login fallidos
        $this->query("CREATE TABLE IF NOT EXISTS failed_login_attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Tabla de actividad de usuarios
        $this->query("CREATE TABLE IF NOT EXISTS user_activity (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            action VARCHAR(100) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES web_users(id)
        )");
        
        // Tabla de rate limiting
        $this->query("CREATE TABLE IF NOT EXISTS rate_limits (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            identifier VARCHAR(255) NOT NULL,
            requests INTEGER DEFAULT 1,
            window_start DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(identifier)
        )");
        
        // Crear usuario admin por defecto si no existe
        $this->createDefaultAdmin();
    }
    
    private function createDefaultAdmin() {
        $adminExists = $this->query("SELECT id FROM web_users WHERE is_admin = 1 LIMIT 1")->fetch();
        
        if (!$adminExists) {
            $defaultPassword = password_hash('admin123', PASSWORD_ARGON2ID);
            $this->query("INSERT INTO web_users (email, password, username, is_admin, is_active) 
                         VALUES ('admin@siesta.local', ?, 'admin', 1, 1)", [$defaultPassword]);
        }
    }
    
    // Métodos para usuarios web
    public function getUserByEmail($email) {
        return $this->query("SELECT * FROM web_users WHERE email = ? AND is_active = 1", [$email])->fetch();
    }
    
    public function getUserById($id) {
        return $this->query("SELECT * FROM web_users WHERE id = ? AND is_active = 1", [$id])->fetch();
    }
    
    public function createUser($userData) {
        $sql = "INSERT INTO web_users (email, password, username, is_active, created_at) 
                VALUES (?, ?, ?, ?, ?)";
        
        $result = $this->query($sql, [
            $userData['email'],
            $userData['password'],
            $userData['username'],
            $userData['is_active'] ? 1 : 0,
            $userData['created_at']
        ]);
        
        return $result ? $this->connection->lastInsertId() : false;
    }
    
    public function updateLastLogin($userId) {
        return $this->query("UPDATE web_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$userId]);
    }
    
    // Métodos para intentos de login fallidos
    public function recordFailedLoginAttempt($email) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        return $this->query("INSERT INTO failed_login_attempts (email, ip_address, user_agent) 
                            VALUES (?, ?, ?)", [$email, $ip, $userAgent]);
    }
    
    public function getFailedLoginAttempts($email) {
        $result = $this->query("SELECT COUNT(*) as count FROM failed_login_attempts 
                               WHERE email = ? AND attempted_at > datetime('now', '-1 hour')", [$email])->fetch();
        return $result['count'] ?? 0;
    }
    
    public function getLastFailedAttempt($email) {
        $result = $this->query("SELECT attempted_at FROM failed_login_attempts 
                               WHERE email = ? ORDER BY attempted_at DESC LIMIT 1", [$email])->fetch();
        return $result['attempted_at'] ?? null;
    }
    
    public function clearFailedLoginAttempts($email) {
        return $this->query("DELETE FROM failed_login_attempts WHERE email = ?", [$email]);
    }
    
    // Métodos para actividad de usuarios
    public function logActivity($userId, $action, $details = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        return $this->query("INSERT INTO user_activity (user_id, action, details, ip_address, user_agent) 
                            VALUES (?, ?, ?, ?, ?)", [$userId, $action, $details, $ip, $userAgent]);
    }
    
    public function getUserActivity($userId, $limit = 50) {
        return $this->query("SELECT * FROM user_activity WHERE user_id = ? 
                            ORDER BY created_at DESC LIMIT ?", [$userId, $limit])->fetchAll();
    }
    
    // Métodos para rate limiting
    public function checkRateLimit($identifier, $maxRequests, $windowSeconds) {
        $windowStart = date('Y-m-d H:i:s', time() - $windowSeconds);
        
        // Limpiar registros antiguos
        $this->query("DELETE FROM rate_limits WHERE window_start < ?", [$windowStart]);
        
        // Obtener registro actual
        $current = $this->query("SELECT * FROM rate_limits WHERE identifier = ?", [$identifier])->fetch();
        
        if (!$current) {
            // Crear nuevo registro
            $this->query("INSERT INTO rate_limits (identifier, requests, window_start) VALUES (?, 1, CURRENT_TIMESTAMP)", [$identifier]);
            return true;
        }
        
        if ($current['requests'] >= $maxRequests) {
            return false;
        }
        
        // Incrementar contador
        $this->query("UPDATE rate_limits SET requests = requests + 1 WHERE identifier = ?", [$identifier]);
        return true;
    }
    
    // Adaptar métodos del bot original para usuarios web
    public function isUserRegisteredWeb($userId) {
        return $this->getUserById($userId) !== false;
    }
    
    public function addUserWeb($email, $username = null) {
        if ($this->getUserByEmail($email)) {
            return false;
        }
        
        $userData = [
            'email' => $email,
            'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_ARGON2ID), // Password temporal
            'username' => $username ?: explode('@', $email)[0],
            'is_active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->createUser($userData);
    }
}