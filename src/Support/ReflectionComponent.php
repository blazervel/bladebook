<?php

namespace Bladepack\Bladepack\Support;

use ReflectionClass;
use Illuminate\Support\{ Str, Collection };

class ReflectionComponent
{
  private array $only = [
    'name',
    'key',
    'description',
    'className',
    'parameters',
    'active',
    'components',
    'inDirectory',
    'isDirectory',
  ];

  public static function make(string $componentClassName): array
  {
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

    $reflect = new self;

    $reflect->name        = $name;
    $reflect->key         = $key;
    $reflect->description = ''; // Use a flatfile db driver? (e.g. https://github.com/ryangjchandler/orbit)
    $reflect->className   = $className;

    // Check for classless view

    $reflect->parameters  = $classParams;
    $reflect->active      = false;
    $reflect->components  = [];
    $reflect->inDirectory = Str::remove('app/View/Components/', Str::remove($name, $path));
    $reflect->isDirectory = false;

    return $reflect->toArray();
  }

  public function toArray()
  {
    $array = [];

    foreach($this->only as $field) :
      $array[$field] = $this->$field;
    endforeach;

    return $array;
  }

  private function makeKey(string $path): string
  {
    $path = Str::replace('\\', '/', $path);
    $name = basename($path);
    $key  = explode('Components/', $path)[1];
    $key  = (new Collection(explode('/', $key)))->map(function($slug){ return Str::snake($slug, '-'); })->join('.');

    return $key;
  }
}
