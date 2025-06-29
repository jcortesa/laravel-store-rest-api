<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStoreRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

class UpdateStoreController
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(UpdateStoreRequest $request, int $id): JsonResponse
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

        \Cache::flush();

        return response()->json(['message' => 'Store updated successfully'], 204);
    }
}
