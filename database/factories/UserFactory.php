<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Auth\User; // ✅ Ajouté pour éviter l'erreur

class UserFactory extends Factory
{
    protected $model = User::class; // ✅ Ajouté pour indiquer le modèle associé

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telephone' => fake()->phoneNumber(),
            'adresse' => fake()->address(),
            'auto_ecole_id' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
