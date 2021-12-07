<?php

use Core\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;


// HOMEPAGE
Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home.index');

// APPLICATION CRUD
Route::crud('categories', CategoryController::class);
Route::crud('products', ProductController::class, ['index']);
Route::get('products/:product', [ProductController::class, 'show'])->name('products.show')->middleware('can:edit,product');
Route::crud('products.suppliers', ProductController::class, ['update', 'create', 'delete']);
Route::crud('suppliers', SupplierController::class);
Route::crud('users', UserController::class);
