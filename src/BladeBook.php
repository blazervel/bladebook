<?php

namespace Blazervel\BladeBook;

use ReflectionClass;

use Blazervel\BladeBook\Support\CreateBladeView;

use Illuminate\Support\{ Str, Collection };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class BladeBook extends Component
{
  public Collection $components;
  public array $stateData;

  public function __construct()
  {
    $this->components();
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
        <div v-scope="{$state}" v-cloak @vue:mounted=\"mounted = true\">
          <template v-if=\"mounted\">{$slot}</template>
        </div>
      @endsection
    blade;
  }

  public function stateData(): array
  {
    return $this->stateData = [
      'components' => $this->components->all(),
    ];
  }

  public function render()
  {
    return View::make(CreateBladeView::fromString($this->view()), [
      'components' => $this->components,
      'state' => htmlspecialchars(json_encode($this->stateData())),
      'slot' => View::make('bladebook::docs'),
    ]);
  }

  public function components(): Collection
  {
    $componentClassFiles = (new FileSystem)->allFiles(
      base_path('app/View/Components')
    );

    foreach ($componentClassFiles as $file) :
      $name = Str::remove(
        '.php', 
        basename($file->getFileName())
      );

      $path        = $file->getRealPath();
      $path        = explode('/', Str::remove(base_path(), $path));
      $path[0]     = 'App';
      $className   = join('\\', $path);
      $classParams = (new ReflectionClass($className))->getConstructor()->getParameters();
      $classParams = (new Collection($classParams))->map(function($parameter){ return json_decode(json_encode($parameter)); })->all();

      $components[] = [
        'name' => $name,
        'className' => $className,
        'parameters' => $classParams,
      ];
    endforeach;

    return $this->components = new Collection($components);
  }
}