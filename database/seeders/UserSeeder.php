<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Central Super Admin',
            'email' => 'superadmin@gmail.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ])->assignRole(RoleEnum::SuperAdmin);

        User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Central Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ])->assignRole(RoleEnum::Admin);

        User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'Central User',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ])->assignRole(RoleEnum::User);
    }
}
