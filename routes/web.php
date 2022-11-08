<?php

use Bladepack\Bladepack\Bladepack;
use Illuminate\Support\Facades\Route;

Route::prefix('bladepack')->middleware('web', 'auth')->group(function () {
    Route::get('/', '\\'.Bladepack::class)->name('bladepack');
});
