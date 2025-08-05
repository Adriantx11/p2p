# 🌐 Siesta Checker Web Application

Una aplicación web moderna para verificación de tarjetas de crédito, convertida desde el bot de Telegram original.

## ✨ Características

- 🔐 **Sistema de Autenticación**: Login/registro seguro con sesiones
- 🛡️ **Seguridad Avanzada**: CSRF protection, rate limiting, validación de entrada
- 💳 **Checker de Tarjetas**: Verificación con múltiples gateways de pago
- 🔍 **Herramientas Integradas**: BIN Lookup, generador de direcciones
- 📱 **Interfaz Responsiva**: Diseño moderno con Bootstrap 5
- 🌍 **Multiidioma**: Soporte para múltiples idiomas
- 📊 **Dashboard Completo**: Estadísticas y monitoreo en tiempo real
- 🚀 **API REST**: Endpoints para todas las funcionalidades

## 🏗️ Arquitectura

```
web/
├── public/                 # Punto de entrada web
│   ├── index.php          # Router principal
│   ├── .htaccess          # Configuración Apache
│   └── assets/            # Assets estáticos
│       ├── css/           # Estilos personalizados
│       ├── js/            # JavaScript
│       └── images/        # Imágenes
├── src/                   # Código fuente
│   ├── classes/           # Clases principales
│   ├── auth/              # Sistema de autenticación
│   ├── api/               # API endpoints
│   ├── middleware/        # Middleware de seguridad
│   ├── config/            # Configuración
│   └── autoload.php       # Autoloader
└── templates/             # Plantillas HTML
    ├── layout.php         # Layout principal
    ├── login.php          # Página de login
    ├── dashboard.php      # Dashboard
    └── checker.php        # Verificador de tarjetas
```

## 🚀 Instalación

### Requisitos

- **PHP 8.0+** con extensiones:
  - sqlite3 (o mysql/pgsql)
  - curl
  - json
  - mbstring
  - xml
  - session
- **Apache/Nginx** con mod_rewrite
- **Navegador moderno** con JavaScript habilitado

### Instalación Rápida

1. **Clonar el repositorio**
```bash
git clone https://github.com/Adriantx11/p2p.git
cd p2p
```

2. **Configurar permisos**
```bash
chmod -R 755 web/
chmod -R 777 database/
chmod -R 777 logs/
```

3. **Configurar servidor web**

**Apache (VirtualHost):**
```apache
<VirtualHost *:80>
    ServerName siesta-checker.local
    DocumentRoot /path/to/p2p/web/public
    
    <Directory /path/to/p2p/web/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name siesta-checker.local;
    root /path/to/p2p/web/public;
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
}
```

4. **Acceder a la aplicación**
```
http://siesta-checker.local
```

### Credenciales por Defecto

- **Email:** admin@siesta.local
- **Contraseña:** admin123

## 🔧 Configuración

### Configuración de Base de Datos

La aplicación usa la misma base de datos que el bot original, pero añade tablas específicas para usuarios web:

```sql
-- Usuarios web
CREATE TABLE web_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100),
    is_active BOOLEAN DEFAULT 1,
    is_admin BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
);

-- Intentos de login fallidos
CREATE TABLE failed_login_attempts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Actividad de usuarios
CREATE TABLE user_activity (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Configuración de Seguridad

Edita `/web/src/config/web_config.php`:

```php
$webConfig = [
    'auth' => [
        'session_lifetime' => 3600 * 24 * 7, // 7 días
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_time' => 900 // 15 minutos
    ],
    'security' => [
        'rate_limit' => [
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000
        ]
    ]
];
```

## 📚 API Endpoints

### Autenticación

- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario
- `GET /logout` - Cerrar sesión

### Checker

- `POST /api/checker` - Verificar tarjetas
  ```json
  {
    "cards": "4111111111111111|12|2025|123\n5555555555554444|01|2024|456",
    "gateway": "paypal"
  }
  ```

### Herramientas

- `POST /api/tools/bin` - BIN Lookup
  ```json
  {
    "bin": "411111"
  }
  ```

- `POST /api/tools/gen` - Generar dirección
  ```json
  {
    "country": "US",
    "state": "CA"
  }
  ```

## 🎨 Personalización

### Temas

La aplicación soporta temas personalizados. Modifica `/web/public/assets/css/app.css`:

```css
:root {
    --primary-gradient: linear-gradient(45deg, #667eea, #764ba2);
    --success-color: #28a745;
    --danger-color: #dc3545;
}
```

### Idiomas

Los idiomas se cargan desde la carpeta `/languages/` del bot original.

## 🛡️ Seguridad

### Características de Seguridad

- ✅ **CSRF Protection**: Tokens únicos para cada sesión
- ✅ **Rate Limiting**: Límites por IP y usuario
- ✅ **SQL Injection Prevention**: Prepared statements
- ✅ **XSS Protection**: Sanitización de entrada
- ✅ **Session Security**: Configuración segura de sesiones
- ✅ **Password Hashing**: Argon2ID
- ✅ **Headers de Seguridad**: CSP, HSTS, etc.

### Configuración de Producción

1. **Habilitar HTTPS**
```apache
# En .htaccess
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

2. **Configurar headers de seguridad**
```php
// En web_config.php
$webConfig['app']['debug'] = false;
```

3. **Configurar firewall**
```bash
# Permitir solo puertos necesarios
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 22/tcp
ufw enable
```

## 📊 Monitoreo

### Logs

Los logs se almacenan en:
- `/logs/web_app.log` - Log principal
- `/logs/security.log` - Eventos de seguridad
- `/logs/api_errors.log` - Errores de API

### Métricas

El dashboard muestra:
- Usuarios activos
- Verificaciones realizadas
- Estado de gateways
- Actividad reciente

## 🚨 Solución de Problemas

### Error: "Headers already sent"
```bash
# Verificar BOM en archivos PHP
head -c 3 web/src/config/web_config.php | od -c
```

### Error: "Session not starting"
```bash
# Verificar permisos de sesión
ls -la /tmp/ | grep sess
```

### Error: "Database not found"
```bash
# Verificar base de datos
ls -la database/
chmod 664 database/siesta.db
```

### Error: "Gateway not working"
```bash
# Verificar conectividad
curl -I https://api.paypal.com
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## ⚠️ Aviso Legal

Esta aplicación es solo para fines educativos y de testing. No usar con tarjetas reales sin autorización. El uso indebido es responsabilidad del usuario.

## 📞 Soporte

- **Issues**: [GitHub Issues](https://github.com/Adriantx11/p2p/issues)
- **Documentación**: [Wiki](https://github.com/Adriantx11/p2p/wiki)
- **Comunidad**: [Discussions](https://github.com/Adriantx11/p2p/discussions)

---

**Desarrollado con ❤️ para la comunidad**