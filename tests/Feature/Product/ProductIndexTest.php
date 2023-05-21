<?php

namespace Product;

use App\Enum\Currency;
use App\Models\Product;
use App\Models\Price;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    private Collection $products;
    private Product $customProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->products = Product::factory(10)
            ->create()
            ->each(fn(Product $product) => $product->prices()->save(
                Price::factory()->make()
            ));

        $this->customProduct = $this->products->first();
    }

    public function test_product_listing(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount($this->products->count(), 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'prices' => [
                        '*' => [
                            'id',
                            'product_id',
                            'price',
                            'currency',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                ],
            ]
        ]);
    }

    public function test_product_name_filter(): void
    {
        $name = fake()->unique()->text(20);
        $this->customProduct->fill(['name' => $name])->save();

        $response = $this->get("/api/products?name=$name");

        $this->assertFilteredProduct($response);
    }

    public function test_product_description_filter(): void
    {
        $description = fake()->unique()->text(50);
        $this->customProduct->fill(['name' => $description])->save();

        $response = $this->get("/api/products?description=$description");

        $this->assertFilteredProduct($response);
    }

    public function test_product_currency_filter(): void
    {
        $currency = Currency::USD;
        Price::query()->update(['currency' => Currency::EURO]);
        $this->customProduct->prices->first()
            ->fill(['currency' => $currency])
            ->save();

        $response = $this->get("/api/products?currency=$currency->value");

        $this->assertFilteredProduct($response);
    }

    public function test_product_price_start_filter(): void
    {
        $price = 2.00;
        Price::query()->update(['price' => 0.50]);
        $this->customProduct->prices->first()
            ->fill(['price' => $price])
            ->save();

        $response = $this->get("/api/products?priceStart=$price");

        $this->assertFilteredProduct($response);
    }

    public function test_product_price_end_filter(): void
    {
        $price = 1.00;
        Price::query()->update(['price' => 2.00]);
        $this->customProduct->prices->first()
            ->fill(['price' => $price])
            ->save();

        $response = $this->get("/api/products?priceEnd=$price");

        $this->assertFilteredProduct($response);
    }

    public function test_product_orderBy_filter(): void
    {
        $columns = [
            'id',
            'name',
            'created_at',
        ];

        foreach ($columns as $column) {
            $response = $this->getJson("/api/products?orderBy=$column&sort=asc");
            $this->assertOrderedProduct($response, $column, false);

            $response = $this->getJson("/api/products?orderBy=$column&sort=desc");
            $this->assertOrderedProduct($response, $column, true);
        }
    }

    public function test_product_limit_filter(): void
    {
        $limit = 5;

        $response = $this->get("/api/products?limit=$limit");

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');
    }

    private function assertOrderedProduct(TestResponse $response, string $column, bool $descending): TestResponse
    {
        $orderedProducts = $this->products->sortBy($column, SORT_REGULAR, $descending);

        return $response->assertStatus(200)
            ->assertJsonCount($this->products->count(), 'data')
            ->assertJson([
                'data' => $orderedProducts->map(function (Product $product) {
                    return [
                        ...$product->toArray(),
                        'prices' => $product->prices->toArray(),
                    ];
                })->values()->toArray(),
            ]);
    }

    private function assertFilteredProduct(TestResponse $response): TestResponse
    {
        $product = $this->customProduct->toArray();
        $product['prices'] = $this->customProduct->prices->toArray();

        return $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$product]
            ]);
    }
}
