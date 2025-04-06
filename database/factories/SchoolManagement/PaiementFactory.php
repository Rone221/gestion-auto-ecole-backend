<?php

namespace Database\Factories\SchoolManagement;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SchoolManagement\Paiement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'auto_ecole_id' => AutoEcole::factory(),
            'montant' => $this->faker->randomFloat(2, 1000, 10000),
            'motif' => $this->faker->randomElement(['abonnement', 'pénalité', 'autre']),
            'statut' => $this->faker->randomElement(['en_attente', 'réglé', 'en_retard']),
            'methode_paiement' => $this->faker->randomElement(['OM', 'Wave', 'Espèces', null]),
            'reference' => $this->faker->uuid,
            'payable_jusqua' => Carbon::now()->addDays(rand(-5, 5)),
            'derniere_relance' => null,
        ];
    }
}
