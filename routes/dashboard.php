<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\OrderItemsController;
use App\Http\Controllers\Dashboard\ImportController;


Route::group([
    'prefix' => '{locale}',
    'middleware' => 'locale',
    'where' => ['locale' => rtrim(implode('|', array_values(config('settings.languages'))), '|')]
], function () {
    Route::group(['prefix' => 'admin', 'middleware' => [
        'access:@administrator#manage-acl#manage-goods'
    ]], function () {
        Route::get('/', [OrderController::class, 'index'])->name('dashboard');

        Route::group(['prefix' => 'acl'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('users', [UserController::class, 'index'])->name('dashboard.users');
            Route::resource('user', UserController::class)->except(['index', 'show']);
            Route::get('roles', [RoleController::class, 'index'])->name('dashboard.roles');
            Route::resource('role', RoleController::class)->except(['index', 'show'])
                ->middleware(['password.confirm','verified']);
            Route::get('permissions', [PermissionController::class, 'index'])->name('dashboard.permissions');
            Route::resource('permission', PermissionController::class)->except(['index', 'show'])
                ->middleware(['password.confirm','verified']);
        });

        Route::group(['prefix' => 'goods'], function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('orders', [OrderController::class, 'index'])->name('dashboard.orders');
            Route::resource('order', OrderController::class)->except(['index', 'show', 'destroy']);
            Route::resource('order-item', OrderItemsController::class)->except(['index', 'show']);
            Route::get('categories', [CategoryController::class, 'index'])->name('dashboard.categories');
            Route::resource('category', CategoryController::class)->except(['index','show']);
            Route::get('products', [ProductController::class, 'index'])->name('dashboard.products');
            Route::resource('product', ProductController::class)->except(['index','show']);
            Route::get('delete-product-image/{product_id}/{image}', [ProductController::class, 'deleteImage'])->name('product.delete-image');
        });

        Route::get('import', [ImportController::class, 'index'])->name('dashboard.import');
        Route::post('import-post', [ImportController::class, 'post'])->name('dashboard.import.post');
    });
});
