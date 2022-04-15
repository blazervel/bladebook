<?php

namespace Blazervel\BladeBook\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeBookServiceProvider extends ServiceProvider 
{
  private string $pathTo = __DIR__ . '/../..';

  public function boot()
  {
    $this->loadViews();
    $this->loadComponents();
    $this->loadRoutes();
    $this->loadTranslations();
  }

  private function loadViews()
  {
    $this->loadViewsFrom(
      "{$this->pathTo}/resources/views", 'bladebook'
    );
  }

  private function loadComponents()
  {
    Blade::componentNamespace(
      'Blazervel\\BladeBook\\View\\Components', 
      'bladebook'
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
      'bladebook'
    );
  }

}