<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\JsonResponse;

class DestroyStoreController
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

        \Cache::flush();

        return response()->json(['message' => 'Store deleted successfully'], 204);
    }
}
