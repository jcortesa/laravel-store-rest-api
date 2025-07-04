<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Store;

final class StoreObserver
{
    public function saved(Store $store): void
    {
        cache()->forget('stores_with_products');
        cache()->forget("store_with_products_$store->id");
    }

    public function updated(Store $store): void
    {
        cache()->forget('stores_with_products');
        cache()->forget("store_with_products_$store->id");
    }

    public function deleted(Store $store): void
    {
        cache()->forget('stores_with_products');
        cache()->forget("store_with_products_$store->id");
    }
}
