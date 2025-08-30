<?php

namespace Database\Factories\Tenant;

use App\enums\Tenant\Material\Status;
use App\enums\Tenant\Material\UnitType;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Material>
 */
class MaterialFactory extends Factory
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
            'unit_type' => fake()->randomElement(UnitType::cases()),
            'status' => fake()->randomElement(Status::cases()),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
