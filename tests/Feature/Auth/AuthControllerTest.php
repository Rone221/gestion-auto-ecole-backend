<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ✅ Assure que le rôle utilisé dans le test existe
        Role::firstOrCreate(['name' => 'moniteur']);
    }

    public function test_user_can_register(): void
    {
        $data = [
            'nom' => 'Alioune',
            'prenom' => 'Fall',
            'email' => 'alioune@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'moniteur' // ✅ Rôle obligatoire
        ];

        $response = $this->postJson('/api/inscription', $data);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'utilisateur', 'roles']);
    }

    public function test_user_can_login(): void
    {
        $user = User::create([
            'nom' => 'Mbaye',
            'email' => 'mbaye@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/connexion', [
            'email' => 'mbaye@test.com',
            'password' => 'password123',
        ]);

        if ($response->status() !== 200) {
            dump($response->json());
        }

        $response->assertOk()
            ->assertJsonStructure(['message', 'token', 'utilisateur']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $response = $this->postJson('/api/connexion', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/deconnexion');

        $response->assertOk()
            ->assertJson(['message' => 'Déconnexion réussie']);
    }
}
