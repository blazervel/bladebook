<?php

use Illuminate\Support\Facades\Route;
use Bladepack\Bladepack\Bladepack;

Route::prefix('bladepack')->middleware('web', 'auth')->group(function(){
  Route::get('/', '\\' . Bladepack::class)->name('bladepack');
});