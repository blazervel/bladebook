<?php declare (strict_types=1);

namespace Bladepack\Bladepack\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadViews();
        $this->loadComponents();
        $this->loadRoutes();
        $this->loadTranslations();
        $this->loadViewComposers();
        $this->loadConfig();
    }

    private function loadViews()
    {
        $this->loadViewsFrom(
            $this->path('resources/views'),
            'bladepack'
        );
    }

    private function loadComponents()
    {
        Blade::componentNamespace(
            'Bladepack\\Bladepack\\View\\Components',
            'bladepack'
        );
    }

    public function loadRoutes()
    {
        $this->loadRoutesFrom(
            $this->path('routes/web.php')
        );
    }

    public function loadTranslations()
    {
        $this->loadTranslationsFrom(
            $this->path('lang'),
            'bladepack'
        );
    }

    public function loadViewComposers()
    {
        $this->app->booted(function () {

            $viewName = 'bladepack::app'; // Need blank version of app layout to get styles etc.

            View::composer([
                'bladepack::bladepack.canvas',
            ], function ($view) use ($viewName) {
                $view->with('appLayout', $viewName);
            });
            
        });
    }

    private function loadConfig()
    {
        $this->publishes([
            $this->path('config/bladepack.php') => config_path('bladepack.php'),
        ], 'bladepack');
    }

    private function path(...$append): string
    {
        $path = Str::remove('/src/Providers', __DIR__);

        return join('/', [$path, ...$append]);
    }
}
