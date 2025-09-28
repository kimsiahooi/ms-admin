<?php

namespace Database\Seeders;

use App\Models\Tenant\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(200)->create();
    }
}
