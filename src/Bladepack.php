<?php

namespace Bladepack\Bladepack;

use Bladepack\Bladepack\Support\CreateBladeView;
use Bladepack\Bladepack\Support\ReflectionComponent;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use ReflectionClass;

class Bladepack extends Component
{
    public array $stateData;

    public function stateData(): array
    {
        return $this->stateData = [
            'mounted' => false,
            'counter' => 0,
            'folders' => $this->componentFolders()->all(),
            'components' => $this->components()->all(),
            'component' => $this->components()->first(),
            'showSearch' => false,
            'tab' => 'docs',
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
        <div v-scope="{{ $state }}" v-cloak @vue:mounted="mounted = true">
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
        $key = explode('Components/', $path)[1];
        $key = (new Collection(explode('/', $key)))->map(function ($slug) {
        return Str::snake($slug, '-');
        })->join('.');

        return $key;
    }

    public function components(): Collection
    {
        $compDir = 'resources/views/components';
        $except = Config::get('bladepack.except') ?: [];
        $only = Config::get('bladepack.only');

        if (! File::exists(
            $compDir = base_path($compDir)
        )) {
            return new Collection([]);
        }

        $componentViewFiles = (new FileSystem)->allFiles($compDir);

        $components = [];

        foreach ($componentViewFiles as $file) {
            $path = Str::remove('.blade.php', $file->getRealPath());
            $path = Str::remove('.php', $path);
            $path = Str::remove(base_path().'/resources/views/components/', $path);
            $name = Str::remove('-', Str::title(basename($path)));
            $key = Str::replace('/', '.', $path);
            $className = 'App\\View\\Components\\';
            $className .= Str::studly($name);

            if ($only !== null && ! in_array($key, $only)) {
                continue;
            }

            if (in_array($key, $except)) {
                continue;
            }

            if (! class_exists($className)) {
                $className = null;
            } else {
                // $components[$key] = ReflectionComponent::make(
                //   componentClassName: $className
                // );

                $classParams = (new ReflectionClass($className))->getConstructor()->getParameters();
                $classParams = (new Collection($classParams))->map(function ($parameter) {
                    $type = $parameter->getType();

                    return [
                        'name' => $parameter->name,
                        'position' => $parameter->getPosition(),
                        'type' => $type && get_class($type) === 'ReflectionNamedType' ? $type->getName() : null,
                        'allowsNull' => $type && get_class($type) === 'ReflectionNamedType' ? $type->allowsNull() : null,
                        'default' => $parameter->isDefaultValueAvailable() ? json_encode($parameter->getDefaultValue()) : 'none',
                    ];
                })->sortBy('position')->all();
            }

            $inDirectory = explode('/', $path);
            $fileName = array_pop($inDirectory);
            $inDirectory = (new Collection($inDirectory))->join('/') ?: null;
            $packs = Config::get("bladepack.packs.{$key}") ?: [];

            // collect($component['props'])
            //   ->filter(fn ($param) => !$param['allowsNull'])
            //   ->map(fn ($param) => [$param['name'] => !in_array($param['type']) ? new $param['type'] : 1])
            //   ->collapse()
            //   ->all();

            $packs = collect($packs)->map(function ($pack) {
                $pack['props'] = collect($pack['props'])->map(function ($val, $key) {
                    if (! (is_array($val) && @class_exists(array_keys($val)[0]))) {
                        return $val;
                    }

                    $className = array_keys($val)[0];
                    $classParameters = array_values($val)[0];

                    if (is_array($classParameters)) {
                        return new $className($classParameters);
                    } else {
                        return new $className;
                    }
                })->all();

                return $pack;
            })->all();

            $components[$key] = [
                'name' => $name,
                'key' => $key,
                'description' => '', // Use a flatfile db driver? (e.g. https://github.com/ryangjchandler/orbit)
                'className' => $className,
                'props' => $classParams ?? [],
                'active' => false,
                'inDirectory' => $inDirectory,
                'path' => "resources/views/components/{$path}.blade.php",
                'packs' => $packs,
            ];
        }

        return new Collection($components);
    }

    public function componentFolders()
    {
        $components = $this->components();
        $folders = [];

        foreach ($components as $component) {
            $key = Str::replace('/', '.', $component['inDirectory'] ?: 'none');

            $folder = $folders[$key] ?? [
                'key' => $key,
                'name' => Str::title(Str::replace('.', '/', $key)),
                'active' => false,
                'open' => $key === 'none',
                'parentComponent' => null,
                'components' => [],
            ];

            $component['folderKey'] = $key;

            $folder['components'][$component['key']] = $component;

            if ($parentComponent = $components->where('key', $key)->first()) {
                unset($folders['none']['components'][$key]);
                $folder['parentComponent'] = $parentComponent;
            }

            $folders[$key] = $folder;
        }

        return (new Collection($folders))->sortBy('name', SORT_STRING);
    }
}
