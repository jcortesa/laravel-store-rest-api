<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SellProductStoreRequest;
use App\Models\ProductStore;
use Illuminate\Http\JsonResponse;

final class SellProductStoreController
{
    private const LOW_STOCK_THRESHOLD = 5;

    public function __invoke(SellProductStoreRequest $request, int $storeId, int $productId): JsonResponse
    {
        $productStore = ProductStore::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if (null === $productStore) {
            return response()->json(['message' => 'Product not found in store'], 404);
        }

        /** @var int $currentStock */
        $currentStock = $productStore->quantity;

        if ($currentStock < $request->quantity) {
            return response()->json(['message' => "Insufficient stock ($currentStock units), sell cannot be made."], 400);
        }

        ProductStore::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->decrement('quantity', $request->quantity);

        /** @var int $currentStock */
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
