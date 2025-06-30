<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\SellProductStoreController;
use Database\Seeders\ProductStoreSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SellProductStoreController::class)]
final class SellProductStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_sell_product_store_on_no_quantity_in_request_then_error_422(): void
    {
        $response = $this->putJson('/api/stores/42/products/24/sell');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test_when_sell_product_store_on_product_not_found_then_error_404(): void
    {
        $payload = ['quantity' => 1];

        $response = $this->putJson('/api/stores/42/products/24/sell', $payload);

        $response->assertStatus(404);
    }

    public function test_when_sell_product_store_on_insufficient_stock_then_error_400(): void
    {
        $this->refreshTestDatabase();
        $this->seed(ProductStoreSeeder::class);
        $payload = ['quantity' => 100];

        $response = $this->putJson('/api/stores/1/products/1/sell', $payload);

        $response->assertStatus(400);
    }

    public function test_when_sell_product_store_on_low_stock_then_status_200_and_warning_message(): void
    {
        $this->refreshTestDatabase();
        $this->seed(ProductStoreSeeder::class);
        $payload = ['quantity' => 8];

        $response = $this->putJson('/api/stores/1/products/1/sell', $payload);

        $response->assertStatus(200);
        $response->assertExactJson([
            'message' => 'Sell done successfully Low stock (2 units), please restock soon.',
        ]);
    }

    public function test_when_sell_product_store_on_stock_then_status_204(): void
    {
        $this->refreshTestDatabase();
        $this->seed(ProductStoreSeeder::class);
        $payload = ['quantity' => 1];

        $response = $this->putJson('/api/stores/1/products/1/sell', $payload);

        $response->assertStatus(204);
        $this->assertDatabaseHas('product_store', [
            'product_id' => 1,
            'store_id' => 1,
            'quantity' => 9,
        ]);
    }
}
