#!/bin/bash

echo "🚀 Starting Deployment..."

# ✅ YOUR USERNAME FIXED HERE
PROJECT_PATH="/home/icetyrwa/public_html/setec-cms-system-finals"

cd $PROJECT_PATH || exit

echo "📥 Pull latest code..."
git checkout main
git pull origin main

echo "📦 Install Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🧹 Clear cache..."
php artisan o:c

echo "⚙️ Rebuild cache..."
php artisan o:c

echo "🗄 Run migrations..."
php artisan migrate --force

echo "🔗 Fix storage..."
php artisan storage:link

echo "🔐 Fix permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Deployment Finished!"
