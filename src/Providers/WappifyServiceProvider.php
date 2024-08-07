<?php

declare(strict_types=1);

namespace AiluraCode\Wappify\Providers;

use AiluraCode\Wappify\Console\Commands\RunWhatsappQueue;
use AiluraCode\Wappify\Http\Middleware\AuthMiddleware;
use AiluraCode\Wappify\Http\Middleware\FacebookMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WappifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/wappify.php', 'wappify');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix(config('wappify.api.prefix', 'api'))
            ->group(function (): void {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
            });
        $this->publishes([
            __DIR__ . '/../../config/wappify.php' => config_path('wappify.php'),
        ], 'config');

        $stub = __DIR__ . '/../../database/migrations/_create_wappify_table.php';
        $file = database_path('migrations/' . date('Y_m_d_His', time()) . '_create_wappify_table.php');
        if (empty(glob(database_path('migrations/*_create_wappify_table.php')))) {
            $this->publishes([$stub => $file], 'migrations');
        }
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunWhatsappQueue::class,
            ]);
        }
        // @phpstan-ignore-next-line
        $this->app['router']
            ->aliasMiddleware(config('wappify.middleware.facebook.name'), FacebookMiddleware::class);
        // @phpstan-ignore-next-line
        $this->app['router']
            ->aliasMiddleware(config('wappify.middleware.auth.name'), AuthMiddleware::class);
        require_once __DIR__ . '/../helpers.php';
    }
}
