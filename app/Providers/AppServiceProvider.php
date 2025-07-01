<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use App\Observers\ProductObserver;
use App\Observers\ProductStoreObserver;
use App\Observers\StoreObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Store::observe(StoreObserver::class);
        Product::observe(ProductObserver::class);
        ProductStore::observe(ProductStoreObserver::class);
    }
}
