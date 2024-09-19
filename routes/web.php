<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('map');
});

Route::get('/map',function (){
    return view('map');
})->name('map');
