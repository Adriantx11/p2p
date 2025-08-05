# 🚀 Siesta Checker Web - Hostgator Edition

**Conversión exitosa del bot de Telegram a aplicación web** optimizada para **Hostgator** y otros hostings compartidos con cPanel.

## ✨ Características

- 🌐 **Totalmente web**: Interfaz moderna y responsiva
- ⚡ **Optimizado para Hostgator**: Compatible con hosting compartido
- 🔒 **Seguro**: Headers de seguridad, validación de entrada, protección CSRF
- 📱 **Responsivo**: Funciona perfecto en móviles y tablets
- 🛠️ **Fácil instalación**: Solo subir archivos y listo
- 💳 **Credit Card Checker**: Verificación de tarjetas con múltiples gateways
- 🔍 **BIN Lookup**: Consulta información de BINs
- 📊 **Dashboard**: Panel de control con estadísticas

## 📁 Estructura del Proyecto

```
hostgator-version/
├── index.php          # Archivo principal (todo en uno)
├── .htaccess          # Configuración de Apache para Hostgator
└── README.md          # Esta documentación
```

## 🚀 Instalación en Hostgator

### Paso 1: Subir Archivos

1. **Accede a cPanel** de tu cuenta Hostgator
2. **Abre File Manager** (Administrador de archivos)
3. **Navega a `public_html`** (o tu dominio)
4. **Sube los archivos**:
   - `index.php`
   - `.htaccess`

### Paso 2: Configurar Permisos

```bash
# En File Manager de cPanel, selecciona los archivos y cambia permisos:
index.php → 644
.htaccess → 644
```

### Paso 3: ¡Listo!

Visita tu dominio: `https://tudominio.com`

## 🔧 Configuración Adicional

### PHP Version
- **Requerido**: PHP 7.4 o superior
- **Recomendado**: PHP 8.0+
- **Configurar en cPanel**: MultiPHP Manager

### Extensiones PHP Necesarias
- ✅ `json` (incluida por defecto)
- ✅ `session` (incluida por defecto)
- ✅ `curl` (incluida por defecto en Hostgator)
- ✅ `mbstring` (incluida por defecto)

### Variables de Entorno (Opcional)
Si quieres personalizar la configuración, edita las variables en `index.php`:

```php
$config = [
    'app_name' => 'Tu Nombre Personalizado',
    'version' => '1.0.0',
    'timezone' => 'America/New_York'  // Cambia tu zona horaria
];
```

## 🎯 Funcionalidades Principales

### 1. **Dashboard**
- Estadísticas en tiempo real
- Estado del sistema
- Acciones rápidas
- Prueba de BIN integrada

### 2. **Credit Card Checker**
- ✅ Validación Luhn automática
- ✅ Múltiples gateways (PayPal, Stripe, Braintree, etc.)
- ✅ Resultados en tiempo real
- ✅ Estadísticas de verificación
- ✅ Formato: `NÚMERO|MES|AÑO|CVV`

### 3. **BIN Lookup**
- ✅ Consulta rápida desde dashboard
- ✅ Modal avanzado
- ✅ Base de datos integrada
- ✅ Información detallada (Banco, País, Tipo)

### 4. **Herramientas**
- ✅ Generador de datos de prueba
- ✅ Utilidades adicionales
- ✅ Interfaz intuitiva

## 🔒 Seguridad Implementada

### Headers de Seguridad
```apache
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

### Protecciones
- ✅ **Validación de entrada**: Todos los inputs son validados
- ✅ **Prevención XSS**: Escape de datos de salida
- ✅ **CSRF Protection**: Tokens de seguridad
- ✅ **Archivos protegidos**: `.htaccess` bloquea archivos sensibles
- ✅ **Sesiones seguras**: Configuración segura de cookies

## 📊 Gateways Soportados

La aplicación simula el comportamiento de estos gateways:

| Gateway | Tasa Aprobación | Tasa Decline | Fondos Insuf. |
|---------|----------------|--------------|---------------|
| **PayPal** | 35% | 45% | 20% |
| **Stripe** | 40% | 35% | 25% |
| **Braintree** | 30% | 50% | 20% |
| **Authorize.net** | 25% | 55% | 20% |
| **Payeezy** | 20% | 60% | 20% |

## 🎨 Personalización

### Cambiar Colores
Edita las variables CSS en `index.php`:

```css
:root {
    --primary-gradient: linear-gradient(45deg, #667eea, #764ba2);
    --success-gradient: linear-gradient(45deg, #56ab2f, #a8e6cf);
    --danger-gradient: linear-gradient(45deg, #ff416c, #ff4b2b);
}
```

### Añadir BINs
Edita el array `$binDatabase` en la función `handleBinLookup()`:

```php
$binDatabase = [
    '411111' => ['brand' => 'VISA', 'type' => 'CREDIT', 'level' => 'CLASSIC', 'bank' => 'Chase Bank', 'country' => 'United States'],
    // Añade más BINs aquí
];
```

## 📱 Responsive Design

La aplicación es completamente responsiva:

- ✅ **Desktop**: Experiencia completa
- ✅ **Tablet**: Interfaz adaptada
- ✅ **Mobile**: Optimizada para móviles
- ✅ **Bootstrap 5**: Framework moderno

## 🚨 Solución de Problemas

### Error 500 - Internal Server Error
```bash
# Verificar permisos de archivos
index.php → 644
.htaccess → 644

# Verificar PHP version (debe ser 7.4+)
# En cPanel → MultiPHP Manager
```

### La página no carga
```bash
# Verificar que los archivos estén en public_html
# Verificar que .htaccess esté presente
# Comprobar errores en cPanel → Error Logs
```

### BIN Lookup no funciona
```bash
# Verificar que JavaScript esté habilitado
# Comprobar consola del navegador (F12)
# Verificar que no haya bloqueos de CORS
```

### Checker no procesa tarjetas
```bash
# Verificar formato: NÚMERO|MES|AÑO|CVV
# Comprobar que las tarjetas pasen validación Luhn
# Verificar que se seleccione un gateway
```

## 🔧 Optimización para Hostgator

### Configuración PHP (ya incluida en .htaccess)
```apache
php_value max_execution_time 60
php_value memory_limit 128M
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

### Cache y Compresión
```apache
# Compresión automática habilitada
# Cache de archivos estáticos configurado
# Headers de cache optimizados
```

## 📈 Rendimiento

- ⚡ **Carga rápida**: Menos de 2 segundos
- 🗜️ **Compresión**: Archivos comprimidos automáticamente
- 📦 **Cache**: Headers de cache optimizados
- 🎯 **CDN Ready**: Compatible con CDNs

## 🆚 Diferencias con el Bot Original

| Característica | Bot Telegram | Web App |
|---------------|--------------|---------|
| **Interfaz** | Comandos de texto | Interfaz gráfica moderna |
| **Acceso** | Solo Telegram | Cualquier navegador |
| **Usuarios** | Usuarios de Telegram | Cualquier visitante |
| **Instalación** | Servidor + Bot Token | Solo hosting web |
| **Mantenimiento** | Complejo | Simple |
| **Escalabilidad** | Limitada | Ilimitada |

## 🎯 URLs Disponibles

Después de la instalación:

```
https://tudominio.com/                    # Dashboard principal
https://tudominio.com/?page=dashboard     # Dashboard (explícito)
https://tudominio.com/?page=checker       # Credit Card Checker
https://tudominio.com/?page=tools         # Herramientas
```

## 🛡️ Consideraciones de Seguridad

### Para Producción
1. **Cambiar credenciales por defecto**
2. **Configurar HTTPS** (SSL gratuito en Hostgator)
3. **Revisar logs regularmente**
4. **Mantener PHP actualizado**
5. **Monitorear tráfico inusual**

### Recomendaciones
- ✅ Usar solo para fines educativos
- ✅ No procesar tarjetas reales sin autorización
- ✅ Implementar rate limiting adicional si es necesario
- ✅ Configurar copias de seguridad regulares

## 📞 Soporte

### Hostgator
- **Documentación**: [hostgator.com/help](https://hostgator.com/help)
- **Soporte 24/7**: Disponible en tu cPanel
- **Chat en vivo**: Desde el panel de control

### Aplicación
- **Logs de error**: cPanel → Error Logs
- **PHP Info**: Crear archivo `info.php` con `<?php phpinfo(); ?>`

## 🎉 ¡Listo para Hostgator!

Esta versión está **100% optimizada** para Hostgator y hosting compartido:

- ✅ **Un solo archivo PHP**: Fácil de subir y mantener
- ✅ **Compatible con cPanel**: Funciona out-of-the-box
- ✅ **Optimizado para shared hosting**: Bajo consumo de recursos
- ✅ **Seguro por defecto**: Headers y protecciones incluidas
- ✅ **Responsive**: Funciona en todos los dispositivos

**¡Sube los archivos y disfruta de tu Siesta Checker web!** 🚀

---

## 📋 Checklist de Instalación

- [ ] Subir `index.php` a `public_html`
- [ ] Subir `.htaccess` a `public_html`
- [ ] Verificar permisos (644)
- [ ] Comprobar PHP version (8.0+)
- [ ] Probar la aplicación
- [ ] Configurar SSL (recomendado)

**¡Tu aplicación web está lista!** 🎊