<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Product\Price\Currency;
use App\Enums\Tenant\Product\Price\Status;
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
            'status' => Status::ACTIVE->value,
            'product_id' => $product,
            'tenant_id' => $product->tenant_id,
        ];
    }
}
