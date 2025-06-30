<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\DisplayStoresController;
use Database\Seeders\ProductStoreSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(DisplayStoresController::class)]
final class DisplayStoresControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_when_display_then_return_stores(): void
    {
        $this->seed(ProductStoreSeeder::class);

        $response = $this->getJson('/api/stores');

        $response->assertStatus(200);
        $response->assertExactJson([
            [
                'id' => 1,
                'name' => 'Some Store',
                'products' => [
                    [
                        'id' => 1,
                        'name' => 'Product A',
                        'quantity' => 10,
                    ],
                ],
            ],
        ]);
    }
}
