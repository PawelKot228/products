<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enum\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory(10)->create();

        Product::factory(100)->create()
            ->each(
                fn(Product $product) => $product->prices()->saveMany([
                    ProductPrice::factory()->withCurrency(Currency::EURO)->make(),
                    ProductPrice::factory()->withCurrency(Currency::PLN)->make(),
                    ProductPrice::factory()->withCurrency(Currency::USD)->make(),
                ])
            );
    }
}
