<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Plant\Operation\Task\Status;
use App\Models\Tenant\Operation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(2);
        $operation = Operation::inRandomOrder()->first();

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(Status::cases()),
            'operation_id' => $operation->id,
            'tenant_id' => $operation->tenant_id,
        ];
    }
}
