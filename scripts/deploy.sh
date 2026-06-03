#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "==> Mode maintenance"
php artisan down --render="errors::503" || true

echo "==> Dépendances PHP (production)"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Build front"
npm ci
npm run build

echo "==> Migrations"
php artisan migrate --force

echo "==> Lien storage"
php artisan storage:link 2>/dev/null || true

echo "==> Cache Laravel"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Fin maintenance"
php artisan up

echo "Deploy terminé."
