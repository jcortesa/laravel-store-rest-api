<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

class StoreStoreController
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(StoreStoreRequest $request): JsonResponse
    {
        $store = Store::factory()->create(['name' => $request->name]);

        foreach ($request->products ?? [] as $productRequest) {
            $product = Product::updateOrCreate(
                ['id' => $productRequest['id'] ?? null],
                ['name' => $productRequest['name']]
            );

            ProductStore::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'store_id' => $store->id
                ],
                [
                    'product_id' => $product->id,
                    'store_id' => $store->id,
                    'quantity' => $productRequest['quantity'] ?? 0,
                ]
            );
        }

        return response()->json(['message' => 'Store created successfully'], 201);
    }
}
