<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Company::factory(20)->create([
                'tenant_id' => $tenant,
            ]);
        }
    }
}
