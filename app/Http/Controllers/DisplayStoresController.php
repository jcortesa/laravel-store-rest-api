<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

final class DisplayStoresController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): JsonResponse
    {
        $data = cache()->remember('stores_with_products', 60, function () {
            $result = [];
            /** @var list<Store> $stores */
            $stores = Store::with('products')->get();

            foreach ($stores as $store) {
                $result[] = [
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
                ];
            }
            return $result;
        });

        return response()->json($data);
    }
}
