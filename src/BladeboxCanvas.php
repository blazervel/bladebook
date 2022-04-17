<?php

namespace Blazervel\Bladebox;

use ReflectionClass;

use Blazervel\Bladebox\Support\CreateBladeView;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\{ View, Log, File };
use Illuminate\Support\{ Str, Collection, Js };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class BladeboxCanvas extends Component
{
  public string|Closure $component;

  public function __invoke(Container $container, Route $route)
  {
    $this->component = $this->component(
      componentKey: $route->component
    );

    return $this->render();
  }

  public function render()
  {
    return View::make('bladebox::bladebox.canvas', [
      'component' => $this->component,
    ]);
  }

  public function component(string $componentKey): string|Closure
  {
    $dummyParameters = [];
    $componentClass = (new Collection(explode('.', $componentKey)))->map(function($slug){
      return Str::ucfirst(Str::camel($slug));
    })->join('\\');
    $componentClass = "App\\View\\Components\\{$componentClass}";

    return (new $componentClass(...$dummyParameters))->render();
  }
}