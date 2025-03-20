<?php

namespace Database\Factories\SchoolManagement;

use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Database\Eloquent\Factories\Factory;

class AutoEcoleFactory extends Factory
{
    protected $model = AutoEcole::class;

    public function definition()
    {
        return [
            'responsable' => $this->faker->name,
            'nom' => $this->faker->company,
            'adresse' => $this->faker->address,
            'telephone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'statut' => true
        ];
    }
}

