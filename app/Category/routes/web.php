<?php

use App\Product\Http\Controllers\ProductController;
use Core\Facades\Route;

Route::crud('', ProductController::class, ['index', 'create']);
Route::get('/:category', [ProductController::class, 'show'])->name('products.show')->middleware('can:edit,product');

if ( isset($_GLOBAL['is_included']) ) { return; }