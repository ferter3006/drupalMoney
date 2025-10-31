#!/bin/bash

# Script de deployment MoneyLink para producción (instalación fresca)
# Usar: ./deploy_production.sh

echo "🚀 Iniciando deployment de MoneyLink en producción..."

# 1. Actualizar código desde Git
echo "📥 Actualizando código desde repositorio..."
git pull origin main

# 2. Instalar dependencias
echo "📦 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader

# 3. Copiar configuración de producción
echo "⚙️ Configurando settings para producción..."
cp settings.production.php web/sites/default/settings.php

# 4. Configurar permisos
echo "🔐 Configurando permisos..."
chmod 755 web/sites/default
chmod 644 web/sites/default/settings.php
mkdir -p web/sites/default/files
chmod 775 web/sites/default/files

# 5. Instalar Drupal desde cero (solo primera vez)
if [ ! -f "web/sites/default/files/.drupal_installed" ]; then
    echo "🔧 Instalando Drupal desde cero..."
    ./vendor/bin/drush site:install standard \
        --db-url=mysql://moneylink_user:TU_PASSWORD@localhost/moneylink_prod \
        --site-name="MoneyLink" \
        --account-name=admin \
        --account-pass=admin_password_segura \
        --yes
    
    # Marcar como instalado
    touch web/sites/default/files/.drupal_installed
    
    # Habilitar módulos MoneyLink
    echo "🔌 Habilitando módulos MoneyLink..."
    ./vendor/bin/drush en moneylink_store moneylink_salas moneylink_perfil moneylink_userpanel moneylink_tiquets moneylink_home moneylink_navigation -y
    
else
    echo "✅ Drupal ya está instalado, solo actualizando..."
    # Solo limpiar cache y actualizar BD
    ./vendor/bin/drush cache:rebuild
    ./vendor/bin/drush updatedb -y
fi

echo "✅ Deployment completado exitosamente!"
echo "🌐 Sitio disponible en: https://ferter.es"
echo "👤 Usuario admin: admin"
echo "🔑 Password admin: admin_password_segura"

# Verificar estado
echo "📊 Estado del sitio:"
./vendor/bin/drush status