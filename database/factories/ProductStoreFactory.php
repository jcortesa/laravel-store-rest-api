<?php

namespace Database\Factories;

use App\Models\ProductStore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductStore>
 */
class ProductStoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->uuid(),
            'store_id' => $this->faker->uuid(),
            'quantity' => $this->faker->randomNumber(),
        ];
    }
}
