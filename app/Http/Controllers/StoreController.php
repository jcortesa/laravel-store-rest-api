<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoreRequest $request): JsonResponse
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

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $data = [];
        $store = Store::with('products')->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $data[] = [
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

        return response()->json($data);
    }
}
