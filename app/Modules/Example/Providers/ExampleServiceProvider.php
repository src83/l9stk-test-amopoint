<?php

namespace App\Modules\Example\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutes();
        $this->loadViews();
        $this->loadMigrations();
    }

    protected function loadRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('cabinet')
            ->as('cabinet.')
            ->group(__DIR__.'/../routes/cabinet.php');
    }

    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'example');
    }

    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
