<?php

namespace Bladepack\Bladepack;

use ReflectionClass;

use Bladepack\Bladepack\Support\CreateBladeView;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\{ View, Log, File };
use Illuminate\Support\{ Str, Collection, Js };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class BladepackCanvas extends Component
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
    return View::make('bladepack::bladepack.canvas', [
      'component' => $this->component,
    ]);
  }

  public function component(string $componentKey): string|Closure
  {
    $dummyParameters = [];

    $componentClass = (new Collection(explode('.', $componentKey)))->map(function($slug){
      return Str::studly($slug);
    })->join('\\');

    $componentClass = "App\\View\\Components\\{$componentClass}";

    return (new $componentClass(...$dummyParameters))->render();
  }
}