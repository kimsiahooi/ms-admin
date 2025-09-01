<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\enums\Tenant\Product\Preset\ShelfLifeType;
use App\enums\Tenant\Product\Preset\Status;
use App\Models\Tenant\Machine;
use App\Models\Tenant\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->unique()->sentence(2);
        $shelfLifeDuration = fake()->optional(0.5)->randomFloat(2, 10, 100);

        return [
            'product_id' => $product->id,
            'machine_id' => $machine->id,
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'cavity_quantity' => fake()->numberBetween(0, 10),
            'cavity_type' => fake()->randomElement(CavityType::cases()),
            'cycle_time' => fake()->randomFloat(2, 0, 10),
            'cycle_time_type' => fake()->randomElement(CycleTimeType::cases()),
            'shelf_life_duration' => $shelfLifeDuration,
            'shelf_life_type' => $shelfLifeDuration ? fake()->randomElement(ShelfLifeType::cases()) : null,
            'status' => fake()->randomElement(Status::cases()),
        ];
    }
}
