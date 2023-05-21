<?php

namespace Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductDestroyTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
    }

    public function test_product_destroy_unauthorized(): void
    {
        $response = $this->deleteJson("/api/products/{$this->product->getKey()}");

        $response->assertStatus(401);
    }

    public function test_product_destroy(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->deleteJson("/api/products/{$this->product->getKey()}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products' , ['id' => $this->product->getKey()]);
    }
}
