<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Route;
use App\Models\Tenant\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\RouteTask>
 */
class RouteTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $task = Task::inRandomOrder()->first();

        $route = Route::active()->where('tenant_id', $task->tenant_id)->inRandomOrder()->first();

        return [
            'route_id' => $route->id,
            'task_id' => $task->id,
            'tenant_id' => $task->tenant_id,
        ];
    }
}
