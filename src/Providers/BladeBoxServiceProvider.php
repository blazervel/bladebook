<?php

namespace Blazervel\Bladebox\Providers;

use Illuminate\Support\Facades\{ Blade, View };
use Illuminate\Support\ServiceProvider;

class BladeboxServiceProvider extends ServiceProvider 
{
  private string $pathTo = __DIR__ . '/../..';

  public function boot()
  {
    $this->loadViews();
    $this->loadComponents();
    $this->loadRoutes();
    $this->loadTranslations();
    $this->loadViewComposers();
  }

  private function loadViews()
  {
    $this->loadViewsFrom(
      "{$this->pathTo}/resources/views", 'bladebox'
    );
  }

  private function loadComponents()
  {
    Blade::componentNamespace(
      'Blazervel\\Bladebox\\View\\Components', 
      'bladebox'
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
      'bladebox'
    );
  }

  public function loadViewComposers()
  {
    $this->app->booted(function () {
    
      $appLayoutExists = View::exists($viewName = 'layouts.app') ?: View::exists($viewName = 'app') ?: ($viewName = 'bladebox::app');

      $viewName = 'bladebox::app'; // Need blank version of app layout to get styles etc.

      View::composer([
        'bladebox::bladebox.canvas',
      ], function ($view) use ($viewName) {
        $view->with('appLayout', $viewName);
      });
      
    });
  }

}