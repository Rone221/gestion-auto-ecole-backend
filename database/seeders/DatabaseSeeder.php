<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\SchoolManagement\Abonnement;

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

    }
}
