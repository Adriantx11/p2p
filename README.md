# 🤖 Telegram Bot - Siesta Checker

Un bot de Telegram avanzado para verificación de tarjetas de crédito con múltiples gateways de pago y herramientas integradas.

## ✨ Características

- 🔐 **Múltiples Gateways**: Soporte para PayPal, Stripe, Braintree, y más
- 🌍 **Multiidioma**: Soporte para Español, Inglés, Francés, Italiano, Alemán y Portugués
- 💾 **Base de Datos SQLite**: Almacenamiento ligero y eficiente
- 🛡️ **Anti-Spam**: Protección contra spam y uso excesivo
- 📊 **Herramientas Integradas**: BIN Lookup, Address Generator, Site Checker
- 🔧 **Fácil Despliegue**: Configuración simple y rápida
- 📱 **Interfaz Intuitiva**: Botones inline y navegación fácil

## 🚀 Instalación Rápida

### Requisitos
- PHP 8.0 o superior
- Extensiones PHP: sqlite3, curl, json, mbstring, xml
- VPS con Ubuntu/Debian (recomendado)

### Instalación Manual
```bash
# 1. Clonar el repositorio
git clone https://github.com/Adriantx11/p2p.git
cd p2p

# 2. Instalar dependencias
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-sqlite3 php8.2-curl php8.2-mbstring php8.2-xml php8.2-json -y

# 3. Configurar
mkdir -p database
chmod 755 database
cp Config/Config.example.php Config/Config.php

# 4. Editar configuración
nano Config/Config.php

# 5. Iniciar bot
php index.php
```

## ⚙️ Configuración

### 1. Obtener Token del Bot
1. Ve a [@BotFather](https://t.me/BotFather) en Telegram
2. Envía `/newbot`
3. Sigue las instrucciones
4. Copia el token recibido

### 2. Obtener tu ID de Administrador
1. Envía un mensaje a [@userinfobot](https://t.me/userinfobot)
2. Copia tu ID numérico

### 3. Configurar el Bot
Edita `Config/Config.php`:
```php
<?php
// Configuración del bot
$config['botToken'] = "TU_TOKEN_AQUI";
$config['adminID'] = "TU_ID_DE_ADMIN";
$config['db']['type'] = "sqlite";
$config['db']['path'] = "database/siesta.db";
$config['timeZone'] = "UTC";
$config['anti_spam_timer'] = "30";
```

## 🏗️ Estructura del Proyecto

```
p2p/
├── Admins/           # Funciones de administración
├── Class/            # Clases principales
├── Config/           # Archivos de configuración
├── Functions/        # Funciones auxiliares
├── Gates/            # Gateways de pago
├── languages/        # Archivos de idiomas
├── Plantillas/       # Plantillas de mensajes
├── Responses/        # Respuestas de gateways
├── Tools/            # Herramientas del bot
├── database/         # Base de datos SQLite
├── index.php         # Punto de entrada
└── require.php       # Cargador de archivos
```

## 📊 Comandos del Bot

- `/start` - Iniciar bot
- `/cmds` - Ver comandos disponibles
- `/lang` - Cambiar idioma
- `/tools` - Herramientas disponibles
- `/info` - Información de cuenta

## 🛠️ Gateways Soportados

- **PayPal** - Procesamiento de pagos PayPal
- **Stripe** - Pagos con tarjeta
- **Braintree** - Gateway de PayPal
- **Authorize.net** - Procesamiento de pagos
- **Y más...** - Ver carpeta `Gates/`

## 🔍 Herramientas Integradas

- **BIN Lookup** - Información de tarjetas
- **Address Generator** - Generador de direcciones
- **Site Checker** - Verificador de sitios
- **Card Validator** - Validador de tarjetas

## 🚨 Solución de Problemas

### Error: "SQLite3 no está disponible"
```bash
sudo apt install php8.2-sqlite3 -y
```

### Error: "Bot token invalid"
```bash
# Verificar token
cat Config/Config.php | grep botToken

# Probar token
curl "https://api.telegram.org/botTU_TOKEN/getMe"
```

### Error: "Headers already sent"
```bash
# Verificar espacios en blanco
head -1 Config/Config.php
head -1 Config/Vars.php
```

## 🔒 Seguridad

- ✅ Configuración de firewall
- ✅ Validación de entrada
- ✅ Anti-spam integrado
- ✅ Logs de seguridad
- ✅ Base de datos segura

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Soporte

- **Issues**: [Reportar problemas](https://github.com/Adriantx11/p2p/issues)
- **Discusiones**: [Foro de la comunidad](https://github.com/Adriantx11/p2p/discussions)

## ⭐ Estrellas

Si este proyecto te ha sido útil, ¡dale una estrella! ⭐

---

**Desarrollado con ❤️ para la comunidad de Telegram** 