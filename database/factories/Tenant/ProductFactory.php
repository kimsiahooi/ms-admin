<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\ShelfLifeType;
use App\Models\Tenant;
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
        $shelfLifeDuration = fake()->optional(0.5)->randomFloat(2, 10, 100);

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'shelf_life_duration' => $shelfLifeDuration,
            'shelf_life_type' => $shelfLifeDuration ? fake()->randomElement(ShelfLifeType::cases()) : null,
            'is_active' => fake()->boolean(),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
