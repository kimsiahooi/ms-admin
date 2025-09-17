<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Plant\Operation\Status;
use App\Models\Tenant\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Operation>
 */
class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(2);
        $plant = Plant::inRandomOrder()->first();

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(Status::cases()),
            'plant_id' => $plant->id,
            'tenant_id' => $plant->tenant_id,
        ];
    }
}
