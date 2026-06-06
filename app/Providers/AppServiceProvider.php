<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRailwayDatabase();
        $this->configureRailwayAppUrl();
    }

    private function configureRailwayDatabase(): void
    {
        $railwayHost = env('MYSQLHOST') ?: env('MYSQL_HOST');

        if ($railwayHost === null || $railwayHost === '') {
            return;
        }

        $configuredHost = env('DB_HOST');

        if ($configuredHost !== null && $configuredHost !== '' && $configuredHost !== '127.0.0.1' && $configuredHost !== 'localhost') {
            return;
        }

        config([
            'database.connections.mysql.host' => $railwayHost,
            'database.connections.mysql.port' => env('MYSQLPORT') ?: env('MYSQL_PORT', env('DB_PORT', '3306')),
            'database.connections.mysql.database' => env('MYSQLDATABASE') ?: env('MYSQL_DATABASE') ?: env('DB_DATABASE', 'railway'),
            'database.connections.mysql.username' => env('MYSQLUSER') ?: env('MYSQL_USER') ?: env('DB_USERNAME', 'root'),
            'database.connections.mysql.password' => env('MYSQLPASSWORD') ?: env('MYSQL_PASSWORD') ?: env('DB_PASSWORD', ''),
        ]);
    }

    private function configureRailwayAppUrl(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        $railwayDomain = env('RAILWAY_PUBLIC_DOMAIN');

        if ($railwayDomain) {
            $configuredUrl = rtrim((string) config('app.url'), '/');

            if ($configuredUrl === '' || str_contains($configuredUrl, 'localhost') || str_starts_with($configuredUrl, 'http://')) {
                config(['app.url' => 'https://'.$railwayDomain]);
            }
        }

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
