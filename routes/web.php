<?php

use App\Http\Controllers\DestroyStoreController;
use App\Http\Controllers\DisplayStoresController;
use App\Http\Controllers\SellProductStoreController;
use App\Http\Controllers\ShowStoreController;
use App\Http\Controllers\StoreStoreController;
use App\Http\Controllers\UpdateStoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/stores', StoreStoreController::class)->name('store');
    Route::get('/stores', DisplayStoresController::class)->name('display');
    Route::get('/stores/{id}', ShowStoreController::class)->name('show');
    Route::put('/stores/{id}', UpdateStoreController::class)->name('update');
    Route::delete('/stores/{id}', DestroyStoreController::class)->name('destroy');
    Route::put(
        '/stores/{storeId}/products/{productId}/sell',
        SellProductStoreController::class
    )->name('sellProductStore');
});
