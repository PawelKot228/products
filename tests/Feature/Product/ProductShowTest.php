<?php

namespace Product;

use App\Models\Product;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_product_object(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $product->prices()->save(Price::factory()->make());

        $response = $this->get("/api/products/{$product->getKey()}");

        $response->assertStatus(200);
        $response->assertJson([
            ...$product->toArray(),
            'prices' => $product->prices->toArray(),
        ]);
    }
}
