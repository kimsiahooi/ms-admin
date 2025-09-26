<?php

namespace Database\Seeders;

use App\Models\Tenant\Route;
use App\Models\Tenant\RouteTask;
use App\Models\Tenant\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RouteTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();

        foreach ($routes as $route) {
            $tasks = Task::where('tenant_id', $route->tenant_id)->inRandomOrder()->limit(rand(1, 2))->get();

            foreach ($tasks as $task) {
                RouteTask::factory()->state(new Sequence(fn() => [
                    'route_id' => $route->id,
                    'task_id' => $task->id,
                    'tenant_id' => $task->tenant_id,
                ]))->create();
            }
        }
    }
}
