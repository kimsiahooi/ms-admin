<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Plant\Department\Operation\Status;
use App\Models\Tenant\Department;
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
        $department = Department::inRandomOrder()->first();

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(Status::cases()),
            'department_id' => $department->id,
            'tenant_id' => $department->tenant_id,
        ];
    }
}
