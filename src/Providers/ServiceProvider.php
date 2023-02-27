<?php declare (strict_types=1);

namespace Bladepack\Bladepack\Providers;

use Bladepack\Bladepack\Http\Livewire\Bladepack;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
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
        if (! App::environment(['local', 'testing', 'development'])) {
            return;
        }

        Route::get('bladepack/js/app.js', function () {
            $manifest = File::get($this->path('public/build/manifest.json'));
            $filePath = json_decode($manifest, true)['resources/js/bladepack.ts']['file'];
            $script = File::get($this->path("public/build/{$filePath}"));

            return response($script)->header('Content-Type', 'application/javascript');
        });

        Route::get('bladepack/css/app.css', function () {
            $manifest = File::get($this->path('public/build/manifest.json'));
            $filePath = json_decode($manifest, true)['resources/js/bladepack.css']['file'];
            $script = File::get($this->path("public/build/{$filePath}"));

            return response($script)->header('Content-Type', 'text/css');
        });

        Route::get('bladepack/logo.png', function () {
            $img = File::get($this->path('resources/images/logo.png'));
            return response($img)->header('Content-type','image/png');
        });

        Route::get('bladepack/{a?}', Bladepack::class)->name('bladepack');
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
