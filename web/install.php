#!/usr/bin/env php
<?php

/**
 * Siesta Checker Web - Installation Script
 * 
 * This script automatically sets up the web application
 */

echo "🌐 Siesta Checker Web - Installation Script\n";
echo "==========================================\n\n";

// Check PHP version
$requiredPhpVersion = '8.0.0';
if (version_compare(PHP_VERSION, $requiredPhpVersion, '<')) {
    die("❌ Error: PHP {$requiredPhpVersion} or higher is required. Current version: " . PHP_VERSION . "\n");
}

echo "✅ PHP version check passed: " . PHP_VERSION . "\n";

// Check required extensions
$requiredExtensions = ['sqlite3', 'curl', 'json', 'mbstring', 'xml'];
$missingExtensions = [];

foreach ($requiredExtensions as $extension) {
    if (!extension_loaded($extension)) {
        $missingExtensions[] = $extension;
    }
}

if (!empty($missingExtensions)) {
    die("❌ Error: Missing PHP extensions: " . implode(', ', $missingExtensions) . "\n");
}

echo "✅ PHP extensions check passed\n";

// Create necessary directories
$directories = [
    '../logs',
    '../database',
    'public/assets/images',
    'templates/partials'
];

echo "\n📁 Creating directories...\n";
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "  ✅ Created: {$dir}\n";
        } else {
            echo "  ❌ Failed to create: {$dir}\n";
        }
    } else {
        echo "  ⚠️  Already exists: {$dir}\n";
    }
}

// Set permissions
echo "\n🔒 Setting permissions...\n";
$permissions = [
    '../logs' => 0777,
    '../database' => 0777,
    'public/assets' => 0755,
    'src' => 0755
];

foreach ($permissions as $path => $perm) {
    if (chmod($path, $perm)) {
        echo "  ✅ Set permissions for: {$path}\n";
    } else {
        echo "  ❌ Failed to set permissions for: {$path}\n";
    }
}

// Initialize database
echo "\n🗄️  Initializing database...\n";

try {
    // Load configuration
    require_once 'src/config/web_config.php';
    
    // Create database connection
    $dbPath = $webConfig['database']['path'];
    $dbDir = dirname($dbPath);
    
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
    }
    
    $pdo = new PDO("sqlite:{$dbPath}");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create web-specific tables
    $sql = "
    CREATE TABLE IF NOT EXISTS web_users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        username VARCHAR(100),
        is_active BOOLEAN DEFAULT 1,
        is_admin BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_login DATETIME
    );
    
    CREATE TABLE IF NOT EXISTS failed_login_attempts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45),
        user_agent TEXT,
        attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    
    CREATE TABLE IF NOT EXISTS user_activity (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        action VARCHAR(100) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES web_users(id)
    );
    
    CREATE TABLE IF NOT EXISTS rate_limits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        identifier VARCHAR(255) NOT NULL,
        requests INTEGER DEFAULT 1,
        window_start DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(identifier)
    );
    ";
    
    $pdo->exec($sql);
    echo "  ✅ Database tables created successfully\n";
    
    // Create default admin user
    $adminExists = $pdo->query("SELECT id FROM web_users WHERE is_admin = 1 LIMIT 1")->fetch();
    
    if (!$adminExists) {
        $defaultPassword = password_hash('admin123', PASSWORD_ARGON2ID);
        $stmt = $pdo->prepare("INSERT INTO web_users (email, password, username, is_admin, is_active) VALUES (?, ?, ?, 1, 1)");
        $stmt->execute(['admin@siesta.local', $defaultPassword, 'admin']);
        echo "  ✅ Default admin user created\n";
        echo "     Email: admin@siesta.local\n";
        echo "     Password: admin123\n";
    } else {
        echo "  ⚠️  Admin user already exists\n";
    }
    
} catch (Exception $e) {
    echo "  ❌ Database error: " . $e->getMessage() . "\n";
}

// Create sample configuration files
echo "\n⚙️  Creating configuration files...\n";

// Create web server configuration examples
$apacheConfig = '
<VirtualHost *:80>
    ServerName siesta-checker.local
    DocumentRoot ' . __DIR__ . '/public
    
    <Directory ' . __DIR__ . '/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/siesta-checker_error.log
    CustomLog ${APACHE_LOG_DIR}/siesta-checker_access.log combined
</VirtualHost>
';

$nginxConfig = '
server {
    listen 80;
    server_name siesta-checker.local;
    root ' . __DIR__ . '/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\. {
        deny all;
    }
    
    access_log /var/log/nginx/siesta-checker_access.log;
    error_log /var/log/nginx/siesta-checker_error.log;
}
';

file_put_contents('apache-vhost.conf.example', $apacheConfig);
file_put_contents('nginx-site.conf.example', $nginxConfig);

echo "  ✅ Created apache-vhost.conf.example\n";
echo "  ✅ Created nginx-site.conf.example\n";

// Test configuration
echo "\n🧪 Testing configuration...\n";

try {
    require_once 'src/autoload.php';
    echo "  ✅ Autoloader working\n";
    
    $webApp = new WebApp($webConfig);
    echo "  ✅ WebApp class instantiated\n";
    
    // Test database connection
    $db = new WebDB($webConfig);
    echo "  ✅ Database connection working\n";
    
} catch (Exception $e) {
    echo "  ❌ Configuration test failed: " . $e->getMessage() . "\n";
}

// Generate security key
echo "\n🔐 Generating security keys...\n";
$securityKey = bin2hex(random_bytes(32));
echo "  ✅ Generated CSRF key: " . substr($securityKey, 0, 16) . "...\n";

// Final instructions
echo "\n🎉 Installation completed!\n";
echo "========================\n\n";

echo "📋 Next steps:\n";
echo "1. Configure your web server (Apache/Nginx)\n";
echo "2. Set up domain/subdomain pointing to: " . __DIR__ . "/public\n";
echo "3. Enable mod_rewrite (Apache) or configure URL rewriting (Nginx)\n";
echo "4. Access the application in your browser\n";
echo "5. Login with: admin@siesta.local / admin123\n";
echo "6. Change the default password!\n\n";

echo "📁 Important paths:\n";
echo "   Web root: " . __DIR__ . "/public\n";
echo "   Configuration: " . __DIR__ . "/src/config/web_config.php\n";
echo "   Logs: " . dirname(__DIR__) . "/logs/\n";
echo "   Database: " . $webConfig['database']['path'] . "\n\n";

echo "🔗 Example URLs:\n";
echo "   http://siesta-checker.local (main site)\n";
echo "   http://siesta-checker.local/login (login page)\n";
echo "   http://siesta-checker.local/dashboard (dashboard)\n";
echo "   http://siesta-checker.local/checker (card checker)\n\n";

echo "⚠️  Security reminders:\n";
echo "   - Change default admin password\n";
echo "   - Enable HTTPS in production\n";
echo "   - Configure firewall rules\n";
echo "   - Set proper file permissions\n";
echo "   - Monitor logs regularly\n\n";

echo "✅ Installation script completed successfully!\n";
echo "🌐 Welcome to Siesta Checker Web!\n\n";

?>