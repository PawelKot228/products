<?php

namespace Product;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    private array $productData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productData = [
            'name' => fake()->text(10),
            'description' => fake()->text(20),
        ];
    }

    public function test_product_store_unauthorized(): void
    {
        $response = $this->postJson('/api/products', $this->productData);

        $response->assertStatus(401);
    }

    public function test_product_store(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/products', $this->productData);

        $response->assertStatus(201);
        $response->assertJson($this->productData);
        $this->assertDatabaseHas('products' , $this->productData);
    }
}
