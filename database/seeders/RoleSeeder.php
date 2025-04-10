<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'guard_name' => 'api',
            'full_name' => 'Administrator',
        ]);
        Role::create([
            'name' => 'Dokter',
            'guard_name' => 'api',
            'full_name' => 'Dokter',
        ]);
    }
}
