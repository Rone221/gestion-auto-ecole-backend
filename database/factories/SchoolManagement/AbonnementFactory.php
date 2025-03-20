<?php

namespace Database\Factories\SchoolManagement;

use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbonnementFactory extends Factory
{
    protected $model = Abonnement::class;

    public function definition()
    {
        return [
            'auto_ecole_id' => AutoEcole::factory(),
            'type' => $this->faker->randomElement(['Mensuel', 'Annuel']),
            'montant' => $this->faker->randomFloat(2, 10000, 50000),
            'date_debut' => $this->faker->date(),
            'date_fin' => $this->faker->date(),
            'statut' => true
        ];
    }
}

