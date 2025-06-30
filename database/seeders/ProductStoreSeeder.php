<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::factory()->create(['name' => 'Product A']);
        $store = Store::factory()->create(['name' => 'Some Store']);

        ProductStore::factory()->create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);
    }
}
