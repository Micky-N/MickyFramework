<?php

use Core\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;


// HOMEPAGE
Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home.index');

// AUTH
Route::get('login', [AuthController::class, 'signIn'])->name('auth.signin');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

// APPLICATION CRUD
Route::crud('categories', CategoryController::class);
Route::crud('products', ProductController::class);
Route::crud('products.suppliers', ProductController::class, ['update', 'create', 'delete']);
Route::crud('suppliers', SupplierController::class);
Route::crud('users', UserController::class);
