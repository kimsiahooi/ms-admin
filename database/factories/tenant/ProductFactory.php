<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'unit_price' => fake()->numberBetween(0.01, 9.99),
            'is_active' => fake()->boolean(),
            'material_id' => Material::inRandomOrder()?->first(),
        ];
    }
}
