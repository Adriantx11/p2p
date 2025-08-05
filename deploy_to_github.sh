#!/bin/bash

# 🚀 Script para Subir Proyecto a GitHub
# Telegram Bot - Deploy to GitHub

set -e

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🚀 Preparando proyecto para GitHub...${NC}"

# Verificar si git está instalado
if ! command -v git &> /dev/null; then
    echo -e "${RED}❌ Git no está instalado. Instalando...${NC}"
    sudo apt update && sudo apt install git -y
fi

# Verificar si estamos en un repositorio git
if [ ! -d ".git" ]; then
    echo -e "${YELLOW}📁 Inicializando repositorio Git...${NC}"
    git init
fi

# Crear archivo .gitignore si no existe
if [ ! -f ".gitignore" ]; then
    echo -e "${YELLOW}📝 Creando .gitignore...${NC}"
    cat > .gitignore << 'EOF'
# Database files
database/
*.db
*.sqlite
*.sqlite3

# Configuration files with sensitive data
Config/Config.php
Config/Vars.php

# Log files
*.log
fatal_error_log.txt
error_log.txt

# Cache and temporary files
cache/
tmp/
temp/

# IDE files
.vscode/
.idea/
*.swp
*.swo

# OS generated files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db

# Backup files
*.bak
*.backup
*.old

# Environment files
.env
.env.local
.env.production

# Composer
vendor/
composer.lock

# Node modules (if any)
node_modules/
npm-debug.log*

# Test files
test_sqlite.php
migrate_to_sqlite.php

# Migration files
MIGRATION_SUMMARY.md
README_SQLITE_MIGRATION.md

# Deployment scripts (optional - remove if you want to include them)
install_vps.sh
quick_deploy.sh
setup_bot.sh
VPS_DEPLOYMENT_GUIDE.md
EJECUTAR_EN_VPS.md
README_VPS.md
deploy_to_github.sh
EOF
fi

# Crear README.md si no existe
if [ ! -f "README.md" ]; then
    echo -e "${YELLOW}📝 Creando README.md...${NC}"
    cat > README.md << 'EOF'
# 🤖 Telegram Bot - Siesta Checker

Un bot de Telegram avanzado para verificación de tarjetas de crédito con múltiples gateways de pago y herramientas integradas.

## ✨ Características

- 🔐 **Múltiples Gateways**: Soporte para PayPal, Stripe, Braintree, y más
- 🌍 **Multiidioma**: Soporte para Español, Inglés, Francés, Italiano, Alemán y Portugués
- 💾 **Base de Datos SQLite**: Almacenamiento ligero y eficiente
- 🛡️ **Anti-Spam**: Protección contra spam y uso excesivo
- 📊 **Herramientas Integradas**: BIN Lookup, Address Generator, Site Checker
- 🔧 **Fácil Despliegue**: Scripts automáticos para VPS
- 📱 **Interfaz Intuitiva**: Botones inline y navegación fácil

## 🚀 Instalación Rápida

### Requisitos
- PHP 8.0 o superior
- Extensiones PHP: sqlite3, curl, json, mbstring, xml
- VPS con Ubuntu/Debian (recomendado)

### Instalación Automática
```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/telegram-bot.git
cd telegram-bot

# Ejecutar script de instalación
chmod +x install_vps.sh
./install_vps.sh
```

## ⚙️ Configuración

1. Copia el archivo de configuración:
```bash
cp Config/Config.example.php Config/Config.php
```

2. Edita la configuración:
```bash
nano Config/Config.php
```

3. Configura tu bot token y admin ID

## 📊 Comandos del Bot

- `/start` - Iniciar bot
- `/cmds` - Ver comandos disponibles
- `/lang` - Cambiar idioma
- `/tools` - Herramientas disponibles
- `/info` - Información de cuenta

## 🔧 Despliegue en VPS

```bash
# Script automático
curl -sSL https://raw.githubusercontent.com/tu-usuario/telegram-bot/main/install_vps.sh | bash
```

## 📝 Licencia

Este proyecto está bajo la Licencia MIT.

---

**Desarrollado con ❤️ para la comunidad de Telegram**
EOF
fi

# Crear LICENSE si no existe
if [ ! -f "LICENSE" ]; then
    echo -e "${YELLOW}📝 Creando LICENSE...${NC}"
    cat > LICENSE << 'EOF'
MIT License

Copyright (c) 2024 Telegram Bot - Siesta Checker

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
EOF
fi

# Agregar archivos al repositorio
echo -e "${BLUE}📦 Agregando archivos al repositorio...${NC}"
git add .

# Hacer commit inicial
echo -e "${BLUE}💾 Haciendo commit inicial...${NC}"
git commit -m "Initial commit: Telegram Bot with SQLite support

- Added multi-language support
- Implemented SQLite database
- Added multiple payment gateways
- Included admin tools and utilities
- Added VPS deployment scripts
- Configured anti-spam protection
- Added comprehensive documentation"

# Configurar usuario git si no está configurado
if [ -z "$(git config user.name)" ]; then
    echo -e "${YELLOW}👤 Configurando usuario Git...${NC}"
    read -p "Ingresa tu nombre de usuario de GitHub: " github_username
    read -p "Ingresa tu email: " github_email
    
    git config user.name "$github_username"
    git config user.email "$github_email"
fi

echo ""
echo -e "${GREEN}✅ Proyecto preparado para GitHub!${NC}"
echo ""
echo "📋 Próximos pasos:"
echo "1. Ve a https://github.com/new"
echo "2. Crea un nuevo repositorio"
echo "3. Ejecuta estos comandos:"
echo ""
echo "git remote add origin https://github.com/TU_USUARIO/TU_REPOSITORIO.git"
echo "git branch -M main"
echo "git push -u origin main"
echo ""
echo "🔗 O ejecuta este script para hacerlo automáticamente:"
echo "bash deploy_to_github.sh"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANTE: Reemplaza TU_USUARIO y TU_REPOSITORIO con tus datos reales${NC}"

# Preguntar si quiere configurar el remote automáticamente
echo ""
read -p "¿Quieres configurar el remote automáticamente? (s/n): " setup_remote

if [[ "$setup_remote" =~ ^[Ss]$ ]]; then
    read -p "Ingresa tu nombre de usuario de GitHub: " github_user
    read -p "Ingresa el nombre del repositorio: " repo_name
    
    echo -e "${BLUE}🔗 Configurando remote...${NC}"
    git remote add origin "https://github.com/$github_user/$repo_name.git"
    git branch -M main
    
    echo -e "${BLUE}🚀 Subiendo a GitHub...${NC}"
    git push -u origin main
    
    echo ""
    echo -e "${GREEN}🎉 ¡Proyecto subido exitosamente a GitHub!${NC}"
    echo "🔗 URL: https://github.com/$github_user/$repo_name"
else
    echo ""
    echo -e "${YELLOW}📝 Ejecuta manualmente los comandos mostrados arriba${NC}"
fi 