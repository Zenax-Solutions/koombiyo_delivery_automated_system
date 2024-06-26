<?php

use App\Http\Controllers\FormController;
use App\Services\koombiyoApi;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {

    return redirect('/admin');
});

Route::get('/order-form/{branch}', [FormController::class, 'view'])->name('order-form');
Route::get('/check-out', [FormController::class, 'checkout'])->name('check-out');
Route::get('/admin/bulk-way-bill', [FormController::class, 'bulk_waybill'])->name('bulkwaybill');
Route::get('/thank-you', [FormController::class, 'thankyouPage'])->name('thank-you');


Route::get('/optimize', function () {

    Artisan::call('storage:link');
    Artisan::call('optimize:clear');

    return 'optimize completed';
});
