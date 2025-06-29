<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

class ShowStoreController
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $id): JsonResponse
    {
        $cacheKey = "store_with_products_$id";
        $data = cache()->remember($cacheKey, 60, function () use ($id) {
            $store = Store::with('products')->find($id);

            if (!$store) {
                return null;
            }

            return [[
                'id' => $store->id,
                'name' => $store->name,
                'products' => $store->products->map(function ($product) {
                    /** @var ProductStore $pivot */
                    $pivot = $product->pivot;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $pivot->quantity,
                    ];
                }),
            ]];
        });

        if (!$data) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        return response()->json($data);
    }
}
