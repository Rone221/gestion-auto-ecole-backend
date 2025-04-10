<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\SchoolManagement\Abonnement;

class AbonnementSeeder extends Seeder
{
    public function run(): void
    {
        $autoEcole = AutoEcole::first();

        if ($autoEcole) {
            Abonnement::factory()->create([
                'auto_ecole_id' => $autoEcole->id,
                'type' => 'Annuel',
                'montant' => 50000,
                'date_debut' => now()->subDays(10),
                'date_fin' => now()->subDays(1),
                'statut' => true,
            ]);
        }
    }
}
