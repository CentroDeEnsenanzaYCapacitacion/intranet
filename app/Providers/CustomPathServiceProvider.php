<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomPathServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function() {
            return base_path(env('PUBLIC_PATH', 'public'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
