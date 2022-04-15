<?php

namespace Blazervel\BladeBook;

use ReflectionClass;

use Illuminate\Support\{ Str, Collection };
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Component;

class BladeBook extends Component
{
  public Collection $components;

  public function __construct()
  {
    $this->components();
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