<?php

use Illuminate\Support\Facades\{ 
  Route, 
  App 
};

use Bladepack\Bladepack\{
  Bladepack,
  BladepackCanvas
};

Route::prefix('bladepack')->group(function(){
  Route::get('/', '\\' . Bladepack::class)->name('bladepack');
  Route::get('{component}/canvas', '\\' . BladepackCanvas::class)->name('bladepack.canvas');
});