<?php

declare(strict_types=1);

namespace App\Observers;

final class ProductObserver
{
    public function saved(): void
    {
        cache()->forget('stores_with_products');
    }

    public function updated(): void
    {
        cache()->forget('stores_with_products');
    }

    public function deleted(): void
    {
        cache()->forget('stores_with_products');
    }
}
