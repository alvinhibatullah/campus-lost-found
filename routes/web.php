<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoundItemController;


Route::get('/', function () {
    return redirect('/found-items');
});

Route::resource('found-items', FoundItemController::class);

