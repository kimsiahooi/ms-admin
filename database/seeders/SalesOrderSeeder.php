<?php

namespace Database\Seeders;

use App\Models\Tenant\SalesOrder;
use Illuminate\Database\Seeder;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesOrder::factory(200)->create();
    }
}
