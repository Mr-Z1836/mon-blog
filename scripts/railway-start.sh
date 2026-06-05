#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "Running migrations and seeding database ..."

php artisan config:clear --ansi
php artisan migrate --force --ansi

if [ "${RUN_DB_SEED:-false}" = "true" ]; then
    php artisan db:seed --force --ansi
fi

php artisan storage:link 2>/dev/null || true

php artisan config:cache --ansi
php artisan route:cache --ansi
php artisan view:cache --ansi

echo "Starting web server on port ${PORT:-8000} ..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
