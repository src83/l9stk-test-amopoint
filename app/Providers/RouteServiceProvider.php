<?php

namespace App\Providers;

use Config;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "index" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/cabinet';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            /**
             * Api
             */
            Route::middleware(['api'])
                ->prefix(Config::get('constants.API_PREFIX'))
                ->as('api.')
                ->group(base_path('routes/api.php'));

            /**
             * Web
             * Залогиненный может выходить из ЛК --> middleware(['web'])
             * Залогиненный всегда будет редиректиться в ЛК --> middleware(['web', 'guest'])
             */
            Route::middleware(['web', 'guest'])
                ->group(base_path('routes/web.php'));

            /**
             * Cabinet
             */
            Route::middleware(['web', 'auth'])
                ->prefix('cabinet')
                ->as('cabinet.')
                ->group(base_path('routes/cabinet.php'));
        });

        Route::pattern('id', '\d+');
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
