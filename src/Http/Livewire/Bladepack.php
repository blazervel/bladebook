<?php

namespace Bladepack\Bladepack\Http\Livewire;

use Debugbar;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class Bladepack extends Component
{
    public Collection $packs;

    public Collection $packNav;

    public array|object|null $currentPack = null;
    
    public ?string $pack = null;

    public ?string $tab = null;

    public function updated(string $name, mixed $value): void
    {
        if ($name === 'pack') {
            $this->currentPack = $this->packs->where('namespace', $value);
        }
    }

    public function boot()
    {
        if (class_exists(Debugbar::class)) {
            Debugbar::disable();
        }

        $this->packs = new Collection([
            [
                'type' => 'Shared',
                'namespace' => 'button',
                'name' => 'Button',
            ],
            [
                'namespace' => 'button.primary',
                'name' => 'Primary',
            ]
        ]);

        // Parent packs
        $navPacks = (
            $this->packs
                ->filter(fn ($pack) => ! Str::contains($pack['namespace'], '.'))
                ->map(function ($pack) {
                    $pack['packs'] = [];
                    return $pack;
                })
        )->all();

        // Child packs
        foreach ($this->packs as $pack) {
            if (! Str::contains($pack['namespace'], '.')) {
                continue;
            }

            $parentNamespace = explode('.', $pack['namespace'])[0];
            
            $parentIndex = (
                (new Collection($navPacks))
                    ->filter(fn ($p) => $p['namespace'] === $parentNamespace)
                    ->keys()
                    ->first()
            );

            // Found no parent, so add it as a parent
            if ($parentIndex === null) {
                $pack['packs'] = [];
                $navPacks[] = $pack;
                continue;
            }

            $navPacks[$parentIndex]['packs'] = array_merge($navPacks[$parentIndex]['packs'] ?: [], [$pack]);
        }

        $nav = [];

        // Headings/Categories
        foreach ($navPacks as $pack) {
            $name = $pack['type'] ?? 'default';
            $packs = [$pack];

            $parentIndex = $name !== null
                ? (
                    (new Collection($nav))
                        ->filter(fn ($p) => $p['name'] === $name)
                        ->keys()
                        ->first()
                )
                : null;

            if ($parentIndex === null) {
                $nav[] = compact('name', 'packs');
                continue;
            }

            $nav[$parentIndex]['packs'] = array_merge($nav[$parentIndex]['packs'], $packs);
        }

        $this->packNav = new Collection($nav);
    }

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('bladepack::livewire.bladepack')->layout('bladepack::app');
    }

    public function show(string $packNamespace): void
    {
        $this->pack = $packNamespace;
        $this->tab = $this->tab ?: 'canvas';
    }
}
