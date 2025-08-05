# 🚀 Siesta Checker - Vercel Serverless

Una versión serverless de Siesta Checker optimizada para **Vercel**, manteniendo las funcionalidades principales pero adaptada para la arquitectura serverless.

## ✨ Características

- 🌐 **Serverless**: Funciona completamente en Vercel
- ⚡ **Rápido**: Carga instantánea con edge functions
- 🔒 **Seguro**: Sin base de datos persistente, más seguro
- 📱 **Responsivo**: Interfaz moderna con Bootstrap 5
- 🛠️ **API REST**: Endpoints serverless para todas las funciones

## 🏗️ Arquitectura Serverless

```
vercel-app/
├── index.php              # Entrada principal (serverless function)
├── api/                   # API endpoints serverless
│   ├── bin.php           # BIN Lookup endpoint
│   └── checker.php       # Card checker endpoint
├── vercel.json           # Configuración de Vercel
├── package.json          # Configuración del proyecto
└── README.md             # Esta documentación
```

## 🚀 Despliegue en Vercel

### Opción 1: Deploy desde GitHub

1. **Fork este repositorio** en tu GitHub
2. **Conecta con Vercel**:
   - Ve a [vercel.com](https://vercel.com)
   - Haz clic en "New Project"
   - Importa desde GitHub
   - Selecciona el repositorio forkeado
   - Selecciona la carpeta `/vercel-app`

3. **Configuración automática**:
   - Vercel detectará automáticamente PHP
   - Usará la configuración de `vercel.json`

4. **Deploy**: ¡Haz clic en Deploy!

### Opción 2: Deploy con Vercel CLI

```bash
# 1. Instalar Vercel CLI
npm i -g vercel

# 2. Navegar al directorio
cd vercel-app/

# 3. Login en Vercel
vercel login

# 4. Deploy
vercel

# 5. Para producción
vercel --prod
```

### Opción 3: Deploy directo (drag & drop)

1. Comprimir la carpeta `vercel-app/`
2. Ir a [vercel.com/new](https://vercel.com/new)
3. Arrastrar el archivo ZIP
4. ¡Deploy automático!

## 🔧 Configuración

### Configuración de Vercel

El archivo `vercel.json` ya está configurado:

```json
{
  "version": 2,
  "functions": {
    "api/**/*.php": {
      "runtime": "vercel-php@0.6.0"
    },
    "*.php": {
      "runtime": "vercel-php@0.6.0"
    }
  },
  "routes": [
    {
      "src": "/api/(.*)",
      "dest": "/api/$1.php"
    },
    {
      "src": "/(.*)",
      "dest": "/index.php"
    }
  ]
}
```

### Variables de Entorno (Opcional)

En el dashboard de Vercel puedes añadir:

```
PHP_VERSION=8.2
NODE_ENV=production
```

## 📚 API Endpoints

### BIN Lookup
```bash
POST /api/bin
Content-Type: application/json

{
  "bin": "411111"
}
```

### Card Checker
```bash
POST /api/checker
Content-Type: application/json

{
  "cards": "4111111111111111|12|2025|123\n5555555555554444|01|2024|456",
  "gateway": "paypal"
}
```

## 🌟 Diferencias con la Versión Completa

### ✅ **Incluido en Vercel:**
- Dashboard interactivo
- BIN Lookup funcional
- Interfaz moderna
- API REST básica
- Validación de tarjetas
- Simulación de gateways

### ⚠️ **Limitaciones Serverless:**
- **Sin base de datos persistente** (usa memoria temporal)
- **Sin autenticación completa** (versión demo)
- **Límite de 10 tarjetas** por verificación
- **Sin logs persistentes**
- **Funciones simuladas** (no gateways reales)

### 🔄 **Para Funcionalidad Completa:**

Si necesitas todas las características, usa:
1. **Vercel + PlanetScale** (MySQL serverless)
2. **Vercel + Supabase** (PostgreSQL + Auth)
3. **Vercel + Redis** (para sesiones)

## 🎯 URLs de Ejemplo

Después del deploy tendrás:

```
https://tu-app.vercel.app/          # Dashboard
https://tu-app.vercel.app/login     # Login (demo)
https://tu-app.vercel.app/checker   # Checker
https://tu-app.vercel.app/api/bin   # API BIN
```

## 🛠️ Desarrollo Local

```bash
# 1. Instalar Vercel CLI
npm install -g vercel

# 2. Desarrollo local
vercel dev

# 3. Abrir navegador
# http://localhost:3000
```

## 📊 Rendimiento

- ⚡ **Cold Start**: ~500ms
- 🚀 **Warm Start**: ~50ms
- 📡 **Edge Locations**: Global
- 💾 **Memory**: 1024MB
- ⏱️ **Timeout**: 10s (Hobby), 60s (Pro)

## 🔒 Seguridad

### ✅ **Características de Seguridad:**
- Headers de seguridad automáticos
- HTTPS obligatorio
- Sin almacenamiento persistente
- Validación de entrada
- CORS configurado

### 🛡️ **Recomendaciones:**
- Usar variables de entorno para secrets
- Implementar rate limiting con Redis
- Añadir autenticación con Auth0/Clerk
- Monitorear con Vercel Analytics

## 🚨 Solución de Problemas

### Error: "Function Timeout"
```bash
# Reducir tiempo de procesamiento
# Limitar número de tarjetas
# Optimizar código PHP
```

### Error: "Memory Limit"
```bash
# Reducir uso de memoria
# Procesar en lotes más pequeños
# Limpiar variables no usadas
```

### Error: "Cold Start Lento"
```bash
# Usar Vercel Pro para mejor rendimiento
# Implementar warming functions
# Optimizar imports PHP
```

## 📈 Escalabilidad

### Limits Vercel:
- **Hobby**: 100GB bandwidth/mes
- **Pro**: 1TB bandwidth/mes
- **Enterprise**: Unlimited

### Para Alto Tráfico:
1. Usar Vercel Pro/Enterprise
2. Implementar CDN
3. Cachear respuestas
4. Usar Edge Functions

## 💡 Próximos Pasos

Para una versión completa en Vercel:

1. **Base de Datos**: PlanetScale o Supabase
2. **Autenticación**: Auth0, Clerk, o NextAuth
3. **Cache**: Vercel KV o Redis
4. **Monitoreo**: Vercel Analytics
5. **Emails**: SendGrid o Resend

## 📞 Soporte

- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **PHP Runtime**: [vercel.com/docs/functions/serverless-functions/runtimes/php](https://vercel.com/docs/functions/serverless-functions/runtimes/php)
- **Issues**: GitHub Issues del proyecto principal

---

## 🎉 ¡Listo para Vercel!

Esta versión está **100% optimizada** para Vercel y se puede desplegar inmediatamente. 

**Deploy URL**: Después del despliegue, tu app estará disponible en una URL como `https://siesta-checker-xxx.vercel.app`

¡Disfruta de tu Siesta Checker serverless! 🚀