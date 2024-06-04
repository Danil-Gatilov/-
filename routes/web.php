<?php

use App\Http\Controllers\AmoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::post('/amo', [AmoController::class, 'index'])->name('amo');

