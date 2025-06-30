<?php
namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\DestroyStoreController;
use Database\Seeders\StoreSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(DestroyStoreController::class)]
final class DestroyStoreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_destroy_store_on_non_existing_store_then_error_404(): void
    {
        $response = $this->deleteJson('/api/stores/42');

        $response->assertStatus(404);
        $response->assertExactJson(['message' => 'Store not found']);
    }

    public function test_when_destroy_store_on__existing_store_then_destroys_store(): void
    {
        $this->refreshTestDatabase();
        $this->seed(StoreSeeder::class);

        $response = $this->deleteJson('/api/stores/1');

        $response->assertStatus(204);
        $this->assertDatabaseEmpty('stores');
    }
}
