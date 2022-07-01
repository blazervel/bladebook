<?php

namespace Bladepack\Bladepack;

use ReflectionClass;

use Bladepack\Bladepack\Support\CreateBladeView;
use Bladepack\Bladepack\Support\ReflectionComponent;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\{ View, Log, File };
use Illuminate\Support\{ Str, Collection, Js };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class Bladepack extends Component
{
  public array $stateData;

  public function stateData(): array
  {
    return $this->stateData = [
      'mounted'    => false,
      'counter'    => 0,
      'folders'    => $this->componentFolders()->all(),
      'components' => $this->components()->all(),
      'component'  => $this->components()->first(),
      'showSearch' => false,
      'tab'        => 'docs',
    ];
  }

  public function __invoke(Container $container, Route $route)
  {
    return $this->render();
  }

  public function view(): string
  {
    return <<<'blade'
      @extends('bladepack::app')
      @section('content')
        <div v-scope="{{ $state }}" v-cloak @vue:mounted="mounted = true; if (window.location.hash && (components[window.location.hash.replace('#', '')] || false)){ component = components[window.location.hash.replace('#', '')]; folders[window.location.hash.replace('#', '')].active = true; folders[component.folderKey].open = true; }">
          <div v-if="mounted">
            @include('bladepack::bladepack.index')
          </div>
        </div>
      @endsection
    blade;
  }

  public function render()
  {
    return View::make(CreateBladeView::fromString($this->view()), [
      'folders' => $this->componentFolders(),
      'components' => $this->components(),
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
    $compDir = 'resources/views/components';

    if (!File::exists(
      $compDir = base_path($compDir)
    )) :
      return new Collection([]);
    endif;

    $componentViewFiles = (new FileSystem)->allFiles($compDir);

    $components = [];

    foreach ($componentViewFiles as $file) :
      $path      = Str::remove('.blade.php', $file->getRealPath());
      $path      = Str::remove('.php', $path);
      $path      = Str::remove(base_path() . '/resources/views/components/', $path);
      $name      = Str::remove('-', Str::title(basename($path)));
      $key       = Str::replace('/', '.', $path);
      $className = 'App\\View\\Components\\';
      $className.= Str::studly($name);

      if (!class_exists($className)) :

        $className = null;

      else :
      
        // $components[$key] = ReflectionComponent::make(
        //   componentClassName: $className
        // );

        $classParams = (new ReflectionClass($className))->getConstructor()->getParameters();
        $classParams = (new Collection($classParams))->map(function($parameter) { 
          $type = $parameter->getType();
          return [
            'name'       => $parameter->name,
            'position'   => $parameter->getPosition(),
            'type'       => $type && get_class($type) === 'ReflectionNamedType' ? $type->getName() : null,
            'allowsNull' => $type && get_class($type) === 'ReflectionNamedType' ? $type->allowsNull() : null,
            'default'    => $parameter->isDefaultValueAvailable() ? json_encode($parameter->getDefaultValue()) : 'none',
          ]; 
        })->all();

      endif;

      $inDirectory = explode('/', $path);
      $fileName = array_pop($inDirectory);
      $inDirectory = (new Collection($inDirectory))->join('/') ?: null;

      $components[$key] = [
        'name'        => $name,
        'key'         => $key,
        'description' => '', // Use a flatfile db driver? (e.g. https://github.com/ryangjchandler/orbit)
        'className'   => $className,
        'parameters'  => $classParams ?? [],
        'active'      => false,
        'inDirectory' => $inDirectory,
        'path'        => "resources/views/components/{$path}.blade.php",
      ];

    endforeach;

    return new Collection($components);
  }

  public function componentFolders()
  {
    $components = $this->components();
    $folders = [];

    foreach ($components as $component) :
      $key = Str::replace('/', '.', $component['inDirectory'] ?: 'none');
      
      $folder = $folders[$key] ?? [
        'key'             => $key,
        'name'            => Str::title(Str::replace('.', '/', $key)),
        'active'          => false,
        'open'            => $key === 'none',
        'parentComponent' => null,
        'components'      => []
      ];

      $component['folderKey'] = $key;

      $folder['components'][$component['key']] = $component;

      if ($parentComponent = $components->where('key', $key)->first()) :
        unset($folders['none']['components'][$key]);
        $folder['parentComponent'] = $parentComponent;
      endif;

      $folders[$key] = $folder;
    endforeach;

    return (new Collection($folders))->sortBy('name', SORT_STRING);
  }
}