<?php

use App\Http\Controllers\HomeController;
use Core\Facades\Route;


Route::get('/', [HomeController::class, 'index']);