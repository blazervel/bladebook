<?php

namespace Bladepack\Bladepack\Http\Livewire;

use Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class Bladepack extends Component
{
    public Collection $components;

    public function boot()
    {
        if (class_exists(Debugbar::class)) {
            Debugbar::disable();
        }
    }

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('bladepack::livewire.bladepack')->layout('bladepack::app');
    }
}
