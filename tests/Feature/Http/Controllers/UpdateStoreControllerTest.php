<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UpdateStoreController;
use Database\Seeders\StoreSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(UpdateStoreController::class)]
final class UpdateStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_update_store_on_no_name_in_request_then_error_422(): void
    {
        $response = $this->putJson('/api/stores/42');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_when_update_store_on_non_existing_store_then_error_404(): void
    {
        $payload = ['name' => 'Some updated store'];

        $response = $this->putJson('/api/stores/42', $payload);

        $response->assertStatus(404);
        $response->assertExactJson(['message' => 'Store not found']);
    }

    public function test_when_update_store_on_existing_store_and_no_products_then_status_204_and_store_without_products(): void
    {
        $this->refreshTestDatabase();
        $this->seed(StoreSeeder::class);
        $payload = [
            'name' => 'Updated Store',
            'products' => [],
        ];

        $response = $this->putJson('/api/stores/1', $payload);

        $response->assertStatus(204);
        $this->assertDatabaseHas('stores', [
            'id' => 1,
            'name' => 'Updated Store',
        ]);
        $this->assertDatabaseEmpty('product_store');
    }

    public function test_when_update_store_on_existing_store_and_products_then_status_204_and_store_with_products(): void
    {
        $this->refreshTestDatabase();
        $this->seed(StoreSeeder::class);
        $payload = [
            'name' => 'Updated Store',
            'products' => [
                ['name' => 'Product A', 'quantity' => 5],
            ],
        ];

        $response = $this->putJson('/api/stores/1', $payload);

        $response->assertStatus(204);
        $this->assertDatabaseHas('stores', [
            'id' => 1,
            'name' => 'Updated Store',
        ]);
        $this->assertDatabaseHas('product_store', [
            'store_id' => 1,
            'product_id' => 1,
            'quantity' => 5,
        ]);
    }
}
