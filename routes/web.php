<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/stores', [StoreController::class, 'store'])->name('store');
    Route::get('/stores', [StoreController::class, 'index'])->name('index');
    Route::get('/stores/{id}', [StoreController::class, 'show'])->name('show');
    Route::put('/stores/{id}', [StoreController::class, 'update'])->name('update');
    Route::delete('/stores/{id}', [StoreController::class, 'destroy'])->name('destroy');
});
