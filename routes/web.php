<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Blazervel\BladeBook\BladeBook;

if (App::environment('local')) :

  Route::get('bladebook', '\\' . BladeBook::class)->name('bladebook');

endif;