<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);

        $this->call([
            TenantSeeder::class,
            TenantUserSeeder::class,
            MachineSeeder::class,
            MaterialSeeder::class,
            ProductSeeder::class,
            ProductPriceSeeder::class,
            ProductPresetSeeder::class,
            BomSeeder::class,
            BomMaterialSeeder::class,
            CustomerSeeder::class,
            CustomerBranchSeeder::class,
            PlantSeeder::class,
            DepartmentSeeder::class,
            OperationSeeder::class,
            DepartmentTenantUserSeeder::class,
            RouteSeeder::class,
            OperationRouteSeeder::class,
            BomRouteSeeder::class,
        ]);
    }
}
