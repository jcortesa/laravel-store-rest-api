<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        $stores = Store::with('products')->get();

        foreach ($stores as $store) {
            $data[] = [
                'id' => $store->id,
                'name' => $store->name,
                'products' => $store->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $store = Store::factory()->create(['name' => $request->name]);

        foreach ($request->products as $productRequest) {
            $product = Product::factory()->create(['name' => $productRequest['name']]);

            ProductStore::factory()->create([
                'product_id' => $product->id,
                'store_id' => $store->id,
                'quantity' => $productRequest['quantity'] ?? 0,
            ]);
        }

        return response()->json(['message' => 'Store created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = [];
        $store = Store::with('products')->find($id);

        $data[] = [
            'id' => $store->id,
            'name' => $store->name,
            'products' => $store->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }),
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        Store::with('products')->find($id)->update(['name' => $request->name]);

        foreach ($request->products as $productRequest) {
            Product::find($productRequest['id'])->update([
               'name' => $productRequest['name'],
            ]);

            ProductStore::where('product_id', $productRequest['id'])
                ->where('store_id', $id)
                ->update([
                    'quantity' => $productRequest['quantity'] ?? 0,
                ]);
        }

        return response()->json(['message' => 'Store updated successfully'], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Store::with('products')->find($id)->delete();

        return response()->json(['message' => 'Store deleted successfully'], 204);
    }
}
