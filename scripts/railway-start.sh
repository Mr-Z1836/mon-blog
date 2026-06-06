#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

if [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    case "${APP_URL:-}" in
        ""|*localhost*|http://*)
            export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
            ;;
    esac
fi

echo "Running migrations and seeding database ..."

php artisan config:clear --ansi
php artisan migrate --force --ansi

if [ "${RUN_DB_SEED:-false}" = "true" ]; then
    echo "RUN_DB_SEED=true → création des comptes démo (harrydedji@gmail.com / password) ..."
    php artisan db:seed --force --ansi
else
    echo "RUN_DB_SEED n'est pas activé → aucun compte démo créé à ce démarrage."
fi

php artisan storage:link 2>/dev/null || true

php artisan config:cache --ansi
php artisan route:cache --ansi
php artisan view:cache --ansi

echo "Starting web server on port ${PORT:-8000} ..."
exec php -S 0.0.0.0:${PORT:-8000} -t public
