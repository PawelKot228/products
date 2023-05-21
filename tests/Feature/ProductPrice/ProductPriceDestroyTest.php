<?php

namespace ProductPrice;

use App\Models\Product;
use App\Models\Price;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPriceDestroyTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private Price $price;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
        $this->price = $this->product->prices()->save(
            Price::factory()->make()
        );
    }

    public function test_product_destroy_unauthorized(): void
    {
        $response = $this->deleteJson("/api/products/{$this->product->getKey()}/prices/{$this->price->getKey()}");

        $response->assertStatus(401);
    }

    public function test_product_destroy(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->deleteJson("/api/products/{$this->product->getKey()}/prices/{$this->price->getKey()}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('prices' , ['id' => $this->price->getKey()]);
    }
}
