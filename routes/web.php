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

// ADMIN PRODUCT
Route::get('admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->middleware('auth')->name('admin.products.index');
Route::get('admin/products/:product', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->middleware(['auth','can:edit,product'])->name('admin.products.show');
Route::post('admin/products/update/:product', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->middleware(['auth','can:edit,product'])->name('admin.products.update');
Route::get('admin/products/delete/:product', [\App\Http\Controllers\Admin\ProductController::class, 'delete'])->middleware(['auth','can:delete,product'])->name('admin.products.delete');