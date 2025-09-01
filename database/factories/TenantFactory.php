<?php

namespace Database\Factories;

use App\Enums\Admin\Tenant\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(1);

        return [
            'id' => Str::slug($name),
            'name' => $name,
            'status' => Status::ACTIVE->value,
        ];
    }
}
