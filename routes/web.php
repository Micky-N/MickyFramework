<?php

use App\Http\Controllers\Admin\NotificationController;
use Core\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Pusher\PushNotifications\PushNotifications;


// HOMEPAGE
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::post('subscription/:subscribe', [NotificationController::class, 'subscribe'])
    ->middleware('auth')
    ->name('notification.subscribe');

Route::get('pusher/beams-auth', function(){
    $beamsClient = new PushNotifications(array(
        "instanceId" => _env('BEAMS_PUBLIC_KEY'),
        "secretKey" => _env('BEAMS_PRIVATE_KEY'),
    ));
    $userID = "App.Models.User.".auth()->id; // If you use a different auth system, do your checks here
    $userIDInQueryParam = \GuzzleHttp\Psr7\ServerRequest::fromGlobals()->getQueryParams()['user_id'];
    if ($userID != $userIDInQueryParam) {
        echo json_encode(['status' => '400', 'message' => 'Inconsistent request']);die;
    } else {
        $beamsToken = $beamsClient->generateToken($userID);
        echo json_encode($beamsToken);die;
    }
});

// APPLICATION CRUD
Route::crud('categories', CategoryController::class);
Route::crud('products', ProductController::class, ['index']);
Route::get('products/:product', [ProductController::class, 'show'])->name('products.show')->middleware('can:edit,product');
Route::crud('products.suppliers', ProductController::class, ['update', 'create', 'delete']);
Route::crud('suppliers', SupplierController::class);
Route::crud('users', UserController::class);

// ADMIN PRODUCT
Route::get('admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->middleware('auth')->name('admin.products.index');
Route::get('admin/products/:product', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->middleware(['auth', 'can:edit,product'])->name('admin.products.show');
Route::post('admin/products/update/:product', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->middleware(['auth', 'can:edit,product'])->name('admin.products.update');
Route::get('admin/products/delete/:product', [\App\Http\Controllers\Admin\ProductController::class, 'delete'])->middleware(['auth', 'can:delete,product'])->name('admin.products.delete');

Route::get('chat', [\App\Http\Controllers\ChatMessageController::class, 'index']);
Route::post('chat/add', [\App\Http\Controllers\ChatMessageController::class, 'addMessage']);