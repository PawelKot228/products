<?php

namespace Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private array $updateData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
        $this->updateData = [
            'name' => fake()->text(10),
            'description' => fake()->text(20),
        ];
    }

    public function test_product_update_unauthorized(): void
    {
        $response = $this->postJson('/api/products', $this->updateData);

        $response->assertStatus(401);
    }

    public function test_product_update(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson("/api/products/{$this->product->id}", $this->updateData);

        $response->assertStatus(204);
        $this->assertDatabaseHas('products' , $this->updateData);
    }
}
