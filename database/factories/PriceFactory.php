<?php

namespace Database\Factories;

use App\Enum\Currency;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 1, 50),
            'currency' => Currency::EURO,
        ];
    }

    public function currency(Currency $currency): self
    {
        return $this->state(function (array $attributes) use ($currency) {
            return [
                'currency' => $currency,
            ];
        });
    }
}
