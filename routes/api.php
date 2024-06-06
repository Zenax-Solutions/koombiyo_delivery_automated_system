<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ResellerController;
use App\Http\Controllers\Api\OrderItemsController;
use App\Http\Controllers\Api\OrderStatusController;
use App\Http\Controllers\Api\ProductItemsController;
use App\Http\Controllers\Api\OrderPaymentsController;
use App\Http\Controllers\Api\ResellerOrdersController;
use App\Http\Controllers\Api\ProductCategoriesController;
use App\Http\Controllers\Api\OrderOrderStatusesController;
use App\Http\Controllers\Api\OrderAllOrderPaymentsController;
use App\Http\Controllers\Api\ProductCategoriesProductsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('users', UserController::class);

        Route::apiResource('resellers', ResellerController::class);

        // Reseller Orders
        Route::get('/resellers/{reseller}/orders', [
            ResellerOrdersController::class,
            'index',
        ])->name('resellers.orders.index');
        Route::post('/resellers/{reseller}/orders', [
            ResellerOrdersController::class,
            'store',
        ])->name('resellers.orders.store');

        Route::apiResource('products', ProductController::class);

        // Product Items
        Route::get('/products/{product}/items', [
            ProductItemsController::class,
            'index',
        ])->name('products.items.index');
        Route::post('/products/{product}/items', [
            ProductItemsController::class,
            'store',
        ])->name('products.items.store');

        Route::apiResource('orders', OrderController::class);

        // Order Items
        Route::get('/orders/{order}/items', [
            OrderItemsController::class,
            'index',
        ])->name('orders.items.index');
        Route::post('/orders/{order}/items', [
            OrderItemsController::class,
            'store',
        ])->name('orders.items.store');

        // Order Order Statuses
        Route::get('/orders/{order}/order-statuses', [
            OrderOrderStatusesController::class,
            'index',
        ])->name('orders.order-statuses.index');
        Route::post('/orders/{order}/order-statuses', [
            OrderOrderStatusesController::class,
            'store',
        ])->name('orders.order-statuses.store');

        // Order All Order Payments
        Route::get('/orders/{order}/all-order-payments', [
            OrderAllOrderPaymentsController::class,
            'index',
        ])->name('orders.all-order-payments.index');
        Route::post('/orders/{order}/all-order-payments', [
            OrderAllOrderPaymentsController::class,
            'store',
        ])->name('orders.all-order-payments.store');

        Route::apiResource(
            'all-product-categories',
            ProductCategoriesController::class
        );

        // ProductCategories Products
        Route::get('/all-product-categories/{productCategories}/products', [
            ProductCategoriesProductsController::class,
            'index',
        ])->name('all-product-categories.products.index');
        Route::post('/all-product-categories/{productCategories}/products', [
            ProductCategoriesProductsController::class,
            'store',
        ])->name('all-product-categories.products.store');
    });
