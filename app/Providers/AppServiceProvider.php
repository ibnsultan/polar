<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        $this->routesRegistrar();

        $this->registerRouteDirective();
        $this->registerBladeDirectives();
    }

    /**
     * Routes Registrar
     */
    public function routesRegistrar()
    {
        Route::middleware(['web', 'auth'])
            ->prefix('app')
            ->group(base_path('routes/app.php'));

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('api')
            ->prefix('hook')
            ->group(base_path('routes/hook.php'));
        
        Route::middleware(['web', 'auth', 'permission:access_admin_panel'])
            ->prefix('admin')
            ->group(base_path('routes/admin.php'));

    }

    /**
     * Blade Directives
     */
    public function registerBladeDirectives()
    {
        View::addExtension('blade.css', 'blade');
        View::addExtension('blade.js', 'blade');
    }

    /**
     * Route Blade Directive
     */
    public function registerRouteDirective()
    {
        // Define @route directive
        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });
    }

}
