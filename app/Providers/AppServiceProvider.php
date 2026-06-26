<?php

namespace App\Providers;

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
        // Register middleware aliases for admin and active_user
        $router = $this->app->make(\Illuminate\Routing\Router::class);
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        $router->aliasMiddleware('active_user', \App\Http\Middleware\ActiveUserMiddleware::class);
        $router->aliasMiddleware('user_only', \App\Http\Middleware\UserOnlyMiddleware::class);
    }
}
