<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::factory()->createMany([
            [
                'id' => 'ms-admin',
                'name' => 'Ms Admin'
            ],
            [
                'id' => 'testing',
                'name' => 'Testing'
            ],
        ]);
    }
}
