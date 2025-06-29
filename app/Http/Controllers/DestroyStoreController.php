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

class DestroyStoreController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(int $id): JsonResponse
    {
        if (null === Store::with('products')->find($id)) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        Store::with('products')->find($id)->delete();

        return response()->json(['message' => 'Store deleted successfully'], 204);
    }
}
