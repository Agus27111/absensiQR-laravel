<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class DemoAndSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin account
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => Hash::make('superadmin123'),
                'super_admin' => true,
                'remember_token' => Str::random(10),
            ]
        );

        // Demo account
        User::updateOrCreate(
            ['username' => 'demo'],
            [
                'password' => Hash::make('demo123'),
                'super_admin' => false,
                'remember_token' => Str::random(10),
            ]
        );
    }
}
