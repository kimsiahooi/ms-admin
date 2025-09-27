<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            TenantUser::factory(9999)->create([
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
