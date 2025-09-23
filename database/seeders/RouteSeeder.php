<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Route::factory(10)->create([
                'tenant_id' => $tenant,
            ]);
        }
    }
}
