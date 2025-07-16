<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
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
            'shelf_life_days' => fake()->optional(0.5)->numberBetween(30, 60),
            'is_active' => fake()->boolean(),
        ];
    }

    public function withMaterials(int $count = 3): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $materials = Material::active()->inRandomOrder()->limit($count)->get();

            if ($materials->isEmpty()) {
                $materials = Material::factory()->count($count)->create();
            }

            $product->materials()->sync($materials);
        });
    }
}
