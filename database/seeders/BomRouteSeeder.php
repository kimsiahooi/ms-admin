<?php

namespace Database\Seeders;

use App\Models\Tenant\Bom;
use App\Models\Tenant\BomRoute;
use App\Models\Tenant\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BomRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();

        foreach ($routes as $route) {
            $boms = Bom::active()->where('tenant_id', $route->tenant_id)->inRandomOrder()->limit(rand(1, 2))->get();

            foreach ($boms as $bom) {
                BomRoute::factory()->state(new Sequence(fn() => [
                    'bom_id' => $bom->id,
                    'route_id' => $route->id,
                    'tenant_id' => $bom->tenant_id,
                ]))->create();
            }
        }
    }
}
