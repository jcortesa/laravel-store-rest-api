<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private CONST LOW_STOCK_THRESHOLD = 5;

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
        $request->validate([
            'name' => 'bail|required|string|max:255',
            'products' => 'array',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

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
    public function show(int $id)
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
    public function update(Request $request, int $id): JsonResponse
    {
        if (null === Store::with('products')->find($id)) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'products' => 'array',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

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
                // @TODO fix composite primary key of ProductStore in order to update
                $productStore->quantity = $productRequest['quantity'];
                $productStore->save();
            }
        }

        return response()->json(['message' => 'Store updated successfully'], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if (null === Store::with('products')->find($id)) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        Store::with('products')->find($id)->delete();

        return response()->json(['message' => 'Store deleted successfully'], 204);
    }

    public function sellProductStore(Request $request, int $storeId, int $productId): JsonResponse
    {
        $productStore = ProductStore::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if (null === $productStore) {
            return response()->json(['message' => 'Product not found in store'], 404);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);
        $currentStock = $productStore->quantity;

        if ($currentStock < $request->quantity) {
            return response()->json(['message' => "Insufficient stock ($currentStock units), sell cannot be made."], 400);
        }

        ProductStore::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->decrement('quantity', $request->quantity);

        $currentStock = ProductStore::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->value('quantity');

        $data = [
            'message' => 'Sell done successfully',
        ];

        if ($currentStock <= self::LOW_STOCK_THRESHOLD) {
            $data['message'] .= " Low stock ($currentStock units), please restock soon.";

            return response()->json($data);
        }

        return response()->json(null, 204);
    }
}
