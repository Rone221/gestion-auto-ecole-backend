<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\Auth\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\SchoolManagement\Abonnement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $autoEcole = AutoEcole::factory()->create();

        // Associer un abonnement à cette auto-école
        Abonnement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'type' => 'Annuel',
            'montant' => 50000,
            'date_debut' => now()->subDays(10),
            'date_fin' => now()->subDays(1), // ✅ Expiré
            'statut' => true, // ✅ Commence avec `true`
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'nom' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        // Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        // Role::firstOrCreate(['name' => 'moniteur', 'guard_name' => 'web']);
        // Role::firstOrCreate(['name' => 'eleve', 'guard_name' => 'web']);
        // Role::firstOrCreate(['name' => 'comptable', 'guard_name' => 'web']);
    }
}
