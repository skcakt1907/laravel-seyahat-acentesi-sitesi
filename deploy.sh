#!/bin/bash
# Marmaris Travel Center — Production deploy script
# Usage (sunucuda): bash ~/public_html/deploy.sh
# Usage (lokalden): ssh user@host 'bash ~/public_html/deploy.sh'

set -e  # herhangi bir komut hata verirse dur

# Proje kök dizini (gerekirse düzenle)
PROJECT_DIR="$HOME/public_html"

cd "$PROJECT_DIR"

echo ">>> [1/7] Maintenance mode AÇILIYOR..."
php artisan down --render="errors::503" || true

echo ">>> [2/7] Git pull (origin/main)..."
git pull origin main

echo ">>> [3/7] Composer install (production)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">>> [4/7] Database migration..."
php artisan migrate --force

echo ">>> [5/7] Cache temizle ve yeniden oluştur..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> [6/7] Storage symlink kontrol..."
php artisan storage:link || true

echo ">>> [7/7] Maintenance mode KAPATILIYOR..."
php artisan up

echo ""
echo "=== DEPLOY TAMAMLANDI ==="
echo "Tarih: $(date '+%Y-%m-%d %H:%M:%S')"
echo "Commit: $(git rev-parse --short HEAD) - $(git log -1 --pretty=%B | head -1)"
