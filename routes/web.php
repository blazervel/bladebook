<?php

use Illuminate\Support\Facades\{ 
  Route, 
  App 
};

use Blazervel\Bladebox\{
  Bladebox,
  BladeboxCanvas
};

if (App::environment('local')) :

  Route::prefix('bladebox')->group(function(){
    Route::get('/', '\\' . Bladebox::class)->name('bladebox');
    Route::get('{component}/canvas', '\\' . BladeboxCanvas::class)->name('bladebox.canvas');
  });

endif;