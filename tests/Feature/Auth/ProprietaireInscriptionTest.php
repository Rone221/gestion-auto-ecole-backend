<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\Auth\User;
use Spatie\Permission\Models\Role;

class ProprietaireInscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'adminAutoEcole']); // 🧠 Important
    }

    public function test_proprietaire_can_register_with_autoecole(): void
    {
        $data = [
            // Utilisateur
            'nom' => 'Pouye',
            'prenom' => 'Mouhamed',
            'email' => 'pouye@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telephone' => '771234567',
            'adresse' => 'Dakar',
            'photo_profil' => null,

            // Auto-école
            'ecole_nom' => 'Auto École Teranga',
            'ecole_adresse' => 'Grand Dakar',
            'ecole_telephone' => '338889900',
            'ecole_email' => 'contact@teranga.com',
        ];

        $response = $this->postJson('/api/auth/register-proprietaire', $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'utilisateur' => ['id', 'nom', 'email'],
                'auto_ecole' => ['id', 'nom'],
                'roles',
                'auth_type',
                'token',
            ]);

        $this->assertDatabaseHas('users', ['email' => $data['email']]);
        $this->assertDatabaseHas('auto_ecoles', ['email' => $data['ecole_email']]);
    }

    public function test_registration_fails_if_required_fields_are_missing(): void
    {
        $response = $this->postJson('/api/auth/register-proprietaire', []);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}
