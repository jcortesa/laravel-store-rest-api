<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellProductStoreRequest;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductStore;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DisplayStoresController
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
