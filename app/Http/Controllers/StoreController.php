<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        /** @var list<Store> $stores */
        $stores = Store::with('products')->get();

        foreach ($stores as $store) {
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
        }

        return response()->json($data);
    }

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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, int $id): JsonResponse
    {
        if (null === Store::with('products')->find($id)) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        Store::with('products')->find($id)->update(['name' => $request->name]);

        foreach ($request->products ?? [] as $productRequest) {
            $product = Product::updateOrCreate(
                ['id' => $productRequest['id'] ?? null],
                ['name' => $productRequest['name'],
            ]);

            $productStore = ProductStore::where('product_id', $product->id)
                ->where('store_id', $id)
                ->first();

            if (null === $productStore) {
                ProductStore::create([
                    'product_id' => $product->id,
                    'store_id' => $id,
                    'quantity' => $productRequest['quantity']
                ]);
            } else {
                $productStore->quantity = $productRequest['quantity'];
                $productStore->save();
            }
        }

        return response()->json(['message' => 'Store updated successfully'], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (null === Store::with('products')->find($id)) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        Store::with('products')->find($id)->delete();

        return response()->json(['message' => 'Store deleted successfully'], 204);
    }
}
