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
            'unit_price' => fake()->numberBetween(0.01, 9.99),
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
