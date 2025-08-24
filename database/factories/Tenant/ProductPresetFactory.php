<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\Models\Tenant\Machine;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\ProductPreset>
 */
class ProductPresetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        $machine = Machine::where('tenant_id', $product->tenant_id)->inRandomOrder()->first();

        return [
            'product_id' => $product->id,
            'machine_id' => $machine->id,
            'name' => fake()->unique()->sentence(2),
            'description' => fake()->sentence(),
            'cavity_quantity' => fake()->numberBetween(0, 10),
            'cavity_type' => fake()->randomElement(CavityType::cases()),
            'cycle_time' => fake()->randomFloat(2, 0, 10),
            'cycle_time_type' => fake()->randomElement(CycleTimeType::cases()),
            'is_active' => fake()->boolean(),
        ];
    }
}
