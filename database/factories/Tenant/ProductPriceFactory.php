<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\Currency;
use App\Models\Tenant;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();

        return [
            'currency' => fake()->randomElement(Currency::cases()),
            'amount' => fake()->randomFloat(2, 10, 99),
            'is_active' => fake()->boolean(),
            'product_id' => $product,
            'tenant_id' => $product->tenant_id,
        ];
    }
}
