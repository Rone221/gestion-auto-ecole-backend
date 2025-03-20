<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Générer des utilisateurs test
        User::factory(10)->create();

        // ✅ Ajouter les rôles par défaut
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'moniteur', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'eleve', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'comptable', 'guard_name' => 'web']);
    }
}
