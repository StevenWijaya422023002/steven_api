<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicalController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/medic', function () {
    return view('pages.plp');
})->name('plp');

Route::get('/medic/{i}', function () {
    return view('pages.pdp');
})->name('pdp');
