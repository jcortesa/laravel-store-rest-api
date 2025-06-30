<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\StoreStoreController;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(StoreStoreController::class)]
final class StoreStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_create_store_on_no_name_then_error_422(): void
    {
        $payload = [
            'products' => [],
        ];

        $response = $this->postJson('/api/stores', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_when_create_store_on_products_without_data_then_error_422(): void
    {
        $payload = [
            'name' => 'Some name',
            'products' => [[]],
        ];

        $response = $this->postJson('/api/stores', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products.0.name', 'products.0.quantity']);
    }

    public function test_when_store_store_on_empty_products_then_store_is_created(): void
    {
        $payload = [
            'name' => 'Some store without products',
            'products' => [],
        ];

        $response = $this->postJson('/api/stores', $payload);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Store created successfully']);
        $this->assertDatabaseHas('stores', ['name' => 'Some store without products']);
    }

    public function test_when_store_store_on_new_products_then_store_is_created(): void
    {
        $payload = [
            'name' => 'Some store with new products',
            'products' => [
                ['name' => 'Product A', 'quantity' => 5],
                ['name' => 'Product B', 'quantity' => 10],
            ],
        ];
        $this->assertDatabaseEmpty('products');

        $response = $this->postJson('/api/stores', $payload);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Store created successfully']);
        $this->assertDatabaseHas('stores', ['name' => 'Some store with new products']);
        $this->assertDatabaseHas('products', ['name' => 'Product A']);
        $this->assertDatabaseHas('products', ['name' => 'Product B']);
    }

    public function test_when_store_store_on_already_existing_products_then_store_is_created_and_products_updated(): void
    {
        $this->refreshTestDatabase();
        $this->seed(ProductSeeder::class);
        $this->assertDatabaseHas('products', ['id' => 1, 'name' => 'Sample Product']);
        $payload = [
            'name' => 'Some store with already existing products',
            'products' => [
                ['id' => 1, 'name' => 'Product A', 'quantity' => 5],
            ],
        ];

        $response = $this->postJson('/api/stores', $payload);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Store created successfully']);
        $this->assertDatabaseHas('stores', ['name' => 'Some store with already existing products']);
        $this->assertDatabaseHas('products', ['id' => 1, 'name' => 'Product A']);
    }
}
