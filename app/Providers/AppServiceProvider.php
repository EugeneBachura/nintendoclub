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
        Blade::directive('localizedRoute', function ($expression) {
            /*$locale = app()->getLocale();
            $defaultLocale = 'en';

            if ($locale == $defaultLocale) {
                return "<?php echo route($expression); ?>";
            }
            return "<?php echo route($expression, ['locale' => $locale]); ?>";*/
            return "<?php echo app('url')->route($expression, ['locale' => app()->getLocale()]); ?>";
        });
    }
}
