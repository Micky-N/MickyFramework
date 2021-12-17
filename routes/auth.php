<?php

use Core\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// AUTH
Route::get('login', [AuthController::class, 'signIn'])->name('auth.signin');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('subscribe', [AuthController::class, 'subscribe'])->name('auth.subscribe');
Route::post('subscribe', [AuthController::class, 'create']);

