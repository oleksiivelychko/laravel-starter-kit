<?php

namespace App\Providers;

use App\Http\Controllers\LocaleController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * This is used by Laravel authentication to redirect users after login.
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'))
            ;

            Route::middleware('web')
                ->group(base_path('routes/web.php'))
                ->group(base_path('routes/dashboard.php'))
            ;

            Route::middleware(['web', 'ajax'])
                ->group(base_path('routes/ajax.php'))
            ;

            Route::prefix('hooks')
                ->middleware('api')
                ->group(base_path('routes/hooks.php'))
            ;
        });

        Route::get('/change-locale/{locale}', [LocaleController::class, 'change'])->name('change-locale');
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
