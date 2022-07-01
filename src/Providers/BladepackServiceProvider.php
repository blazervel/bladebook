<?php

namespace Bladepack\Bladepack\Providers;

use Illuminate\Support\Facades\{ Blade, View };
use Illuminate\Support\ServiceProvider;

class BladepackServiceProvider extends ServiceProvider 
{
  private string $pathTo = __DIR__ . '/../..';

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
      "{$this->pathTo}/resources/views", 'bladepack'
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
      "{$this->pathTo}/routes/web.php"
    );
  }

  public function loadTranslations() 
  {
    $this->loadTranslationsFrom(
      "{$this->pathTo}/lang", 
      'bladepack'
    );
  }

  public function loadViewComposers()
  {
    $this->app->booted(function () {
    
      $appLayoutExists = View::exists($viewName = 'layouts.app') ?: View::exists($viewName = 'app') ?: ($viewName = 'bladepack::app');

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
      "{$this->pathTo}/config/bladepack.php" => config_path('bladepack.php'),
    ], 'bladepack');
  }

}