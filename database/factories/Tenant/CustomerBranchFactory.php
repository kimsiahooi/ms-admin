<?php

namespace Database\Factories\Tenant;

use App\Enums\Tenant\Customer\Branch\Status;
use App\Models\Tenant;
use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\CustomerBranch>
 */
class CustomerBranchFactory extends Factory
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
            'address' => fake()->address(),
            'status' => fake()->randomElement(Status::cases()),
            'customer_id' => Customer::inRandomOrder()->first(),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
