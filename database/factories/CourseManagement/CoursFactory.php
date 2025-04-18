<?php

namespace Database\Factories\CourseManagement;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseManagement\Cours>
 */
class CoursFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['theorique', 'pratique']),
            'date_debut' => now()->addDays(rand(1, 10)),
            'date_fin' => now()->addDays(rand(1, 10))->addHours(2),
            'statut' => 'planifie',
            'moniteur_id' => \App\Models\Auth\User::factory(),
            'eleve_id' => null,
            'auto_ecole_id' => \App\Models\SchoolManagement\AutoEcole::factory(),
        ];
    }
}
