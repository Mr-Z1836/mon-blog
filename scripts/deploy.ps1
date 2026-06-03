# Préparation locale ou déploiement sur serveur Windows (XAMPP / IIS)
# Usage : powershell -ExecutionPolicy Bypass -File scripts\deploy.ps1
#         powershell -ExecutionPolicy Bypass -File scripts\deploy.ps1 -Production

param(
    [switch]$Production
)

$ErrorActionPreference = "Stop"
Set-Location (Join-Path $PSScriptRoot "..")

if (-not (Test-Path ".env")) {
    if ($Production -and (Test-Path ".env.production.example")) {
        Copy-Item ".env.production.example" ".env"
        Write-Host "Fichier .env créé depuis .env.production.example — complète DB_* et lance : php artisan key:generate"
    } else {
        Copy-Item ".env.example" ".env"
        Write-Host "Fichier .env créé — lance : php artisan key:generate"
    }
}

Write-Host "==> Composer"
if ($Production) {
    composer install --no-dev --optimize-autoloader --no-interaction
} else {
    composer install --no-interaction
}

Write-Host "==> NPM build"
npm ci
npm run build

Write-Host "==> Migrations"
php artisan migrate --force

Write-Host "==> Storage link"
php artisan storage:link 2>$null

if ($Production) {
    Write-Host "==> Cache production"
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
} else {
    Write-Host "==> Cache dev effacé"
    php artisan optimize:clear
}

Write-Host "Terminé. Lance : php artisan serve"
