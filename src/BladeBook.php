<?php

namespace Blazervel\BladeBook;

use ReflectionClass;

use Blazervel\BladeBook\Support\CreateBladeView;

use Illuminate\Routing\Route;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\{ View, Log };
use Illuminate\Support\{ Str, Collection, Js };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class BladeBook extends Component
{
  public Collection $components;
  public array $stateData;
  private string $componentDirectory = 'app/View/Components';

  public function __construct()
  {
    $this->components();
  }

  public function stateData(): array
  {
    return $this->stateData = [
      'mounted'    => false,
      'counter'    => 0,
      'components' => $this->components->all(),
      'component'  => $this->components->first(),
      'showSearch' => false,
    ];
  }

  public function __invoke(Container $container, Route $route)
  {
    return $this->render();
  }

  public function view(): string
  {
    return <<<'blade'
      @extends('bladebook::app')
      @section('content')
        <div v-scope="{{ $state }}" v-cloak @vue:mounted="mounted = true; if(window.location.hash.length && (components[window.location.hash] || false)){ component = components[window.location.hash] }">
          <div v-if="mounted">
            @include('bladebook::bladebook.index')
          </div>
        </div>
      @endsection
    blade;
  }

  public function render()
  {
    return View::make(CreateBladeView::fromString($this->view()), [
      'components' => $this->components,
      'state' => Js::from($this->stateData()),
    ]);
  }

  private function makeKey(string $path): string
  {
    $path = Str::replace('\\', '/', $path);
    $name = basename($path);
    $key  = explode('Components/', $path)[1];
    $key  = (new Collection(explode('/', $key)))->map(function($slug){ return Str::snake($slug, '-'); })->join('.');

    return $key;
  }

  public function components(): Collection
  {
    $componentClassFiles = (new FileSystem)->allFiles(
      base_path($this->componentDirectory)
    );

    foreach ($componentClassFiles as $file) :
      $path        = Str::remove('.php', $file->getRealPath());
      $name        = basename($path);
      $path        = Str::remove(base_path() . '/', $path);
      $className   = Str::replace('/', '\\', $path);
      $className   = Str::ucfirst($className); // app -> App
      $key         = $this->makeKey($className);
      $classParams = (new ReflectionClass($className))->getConstructor()->getParameters();
      $classParams = (new Collection($classParams))->map(function($parameter){ 
        $type = $parameter->getType();
        return [
          'name'       => $parameter->name,
          'position'   => $parameter->getPosition(),
          'type'       => $type && get_class($type) === 'ReflectionNamedType' ? $type->getName() : null,
          'allowsNull' => $type && get_class($type) === 'ReflectionNamedType' ? $type->allowsNull() : null,
          'default'    => $parameter->isDefaultValueAvailable() ? json_encode($parameter->getDefaultValue()) : 'none',
        ]; 
      })->all();

      $components[$key] = [
        'name'        => $name,
        'key'         => $key,
        'description' => '', // Use a flatfile db driver? (e.g. https://github.com/ryangjchandler/orbit)
        'className'   => $className,
        'parameters'  => $classParams,
        'active'      => false,
        'components'  => [],
        'inDirectory' => Str::remove('app/View/Components/', Str::remove($name, $path)),
        'isDirectory' => false,
      ];

    endforeach;

    return $this->components = new Collection($components);
  }
}