<?php

use Core\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;


Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home.index');
Route::crud('categories', CategoryController::class);
