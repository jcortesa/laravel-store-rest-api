<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\ShowStoreController;
use Database\Seeders\ProductStoreSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(ShowStoreController::class)]
final class ShowStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_show_on_no_store_then_return_404_status(): void
    {
        $response = $this->getJson('/api/stores/1');

        $response->assertStatus(404);
        $response->assertExactJson(['message' => 'Store not found',]);
    }

    public function test_when_show_existing_store_then_return_store(): void
    {
        $this->seed(ProductStoreSeeder::class);

        $response = $this->getJson('/api/stores/1');

        $response->assertStatus(200);
        $response->assertExactJson([
            'id' => 1,
            'name' => 'Some Store',
            'products' => [
                [
                    'id' => 1,
                    'name' => 'Product A',
                    'quantity' => 10,
                ],
            ],
        ]);
    }
}
