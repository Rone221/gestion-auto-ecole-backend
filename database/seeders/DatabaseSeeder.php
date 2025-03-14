<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\Auth\User;
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
            'nom' => 'Test User',
            'email' => 'test@example.com',
        ]);



        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'moniteur', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'eleve', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'comptable', 'guard_name' => 'web']);
    }
}
