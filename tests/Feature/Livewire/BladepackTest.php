<?php

use App\Http\Livewire\Bladepack;
use Livewire\Livewire;

test('bladepack component can be rendered', function () {

    $component = Livewire::test(Bladepack::class);

    $component->assertStatus(200);
});