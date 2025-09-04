<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Plant::factory(20)->create([
                'tenant_id' => $tenant,
            ]);
        }
    }
}
