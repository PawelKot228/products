<?php

namespace ProductPrice;

use App\Enum\Currency;
use App\Models\Product;
use App\Models\Price;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPriceUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private Price $price;
    private array $updateData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
        $this->price = $this->product->prices()->save(
            Price::factory()->make()
        );

        $this->updateData = [
            'price' => 3.99,
            'currency' => Currency::USD->value,
        ];
    }

    public function test_product_update_unauthorized(): void
    {
        $response = $this->putJson(
            "/api/products/{$this->product->getKey()}/prices/{$this->price->getKey()}",
            $this->updateData
        );

        $response->assertStatus(401);
    }

    public function test_product_update(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson(
            "/api/products/{$this->product->getKey()}/prices/{$this->price->getKey()}",
            $this->updateData
        );

        $response->assertStatus(204);
        $this->assertDatabaseHas('prices' , $this->updateData);
    }
}
