<?php

namespace Database\Factories;

use App\Enum\Currency;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 1, 50),
            'currency' => Currency::EURO,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function withCurrency(Currency $currency): self
    {
        return $this->state(function (array $attributes) use ($currency) {
            return [
                'currency' => $currency,
            ];
        });
    }
}
