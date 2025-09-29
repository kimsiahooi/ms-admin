<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Product\Price\Currency;
use App\Models\Tenant\CustomerBranch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Order>
 */
class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branch = CustomerBranch::inRandomOrder()->first();

        return [
            'code' => Str::slug(fake()->unique()->sentence(3)),
            'currency' => fake()->randomElement(Currency::cases()),
            'remark' => fake()->optional()->sentence(),
            'delivery_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'customer_branch_id' => fake()->boolean() ? $branch->id : null,
            'tenant_id' => $branch->tenant_id,
        ];
    }
}
