<?php

namespace Database\Seeders;

use App\Models\Tenant\Route;
use App\Models\Tenant\OperationRoute;
use App\Models\Tenant\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class OperationRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();

        foreach ($routes as $route) {
            $operations = Operation::where('tenant_id', $route->tenant_id)->inRandomOrder()->limit(rand(2, 4))->get();

            foreach ($operations as $i => $operation) {
                OperationRoute::factory()->state(new Sequence(fn() => [
                    'route_id' => $route->id,
                    'operation_id' => $operation->id,
                    'sequence' => $i,
                    'tenant_id' => $operation->tenant_id,
                ]))->create();
            }
        }
    }
}
