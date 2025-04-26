<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Register Blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Blade Directives
     */
    public function registerBladeDirectives()
    {
        // @route
        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });
    }
}
