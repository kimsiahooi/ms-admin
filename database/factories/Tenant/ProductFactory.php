<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Product\Currency;
use App\enums\Tenant\Product\ShelfLifeType;
use App\Models\Tenant;
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

    public function withCurrencies(int $count = 3): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {

            $currencies = Currency::cases();
            shuffle($currencies);

            $selectedCurrencies = array_slice($currencies, 0, $count);

            foreach ($selectedCurrencies as $currency) {
                $product->prices()->create([
                    'currency' => $currency->value,
                    'price' => fake()->randomFloat(2, 10, 100),
                    'tenant_id' => $product->tenant_id,
                ]);
            }
        });
    }
}
