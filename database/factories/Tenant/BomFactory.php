<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\Bom\Status;
use App\Models\Tenant;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Bom>
 */
class BomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(2);

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(Status::cases()),
            'product_id' => Product::inRandomOrder()->first(),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
