<?php


namespace ProductPrice;

use App\Enum\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPriceStoreTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private array $priceData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
        $this->priceData = [
            'price' => 2.99,
            'currency' => Currency::USD->value,
        ];
    }

    public function test_product_store_unauthorized(): void
    {
        $response = $this->postJson("/api/products/{$this->product->getKey()}/prices", $this->priceData);

        $response->assertStatus(401);
    }

    public function test_product_store(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson("/api/products/{$this->product->getKey()}/prices", $this->priceData);

        $response->assertStatus(201);
        $response->assertJson($this->priceData);
        $this->assertDatabaseHas('prices', [
            ...$this->priceData,
            'product_id' => $this->product->getKey()
        ]);
    }
}
