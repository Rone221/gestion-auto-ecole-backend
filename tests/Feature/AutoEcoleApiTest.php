<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;

class AutoEcoleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 🛡️ Crée le rôle s'il n'existe pas
        Role::firstOrCreate(['name' => 'adminAutoEcole']);
    }

    private function createAuthenticatedUser(): User
    {
        $autoEcole = AutoEcole::factory()->create();
        $user = User::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
        ]);
        $user->assignRole('adminAutoEcole');

        $this->actingAs($user, 'sanctum');

        return $user;
    }

    #[Test]
    public function getAllAutoEcoles()
    {
        $this->createAuthenticatedUser();
        AutoEcole::factory()->count(3)->create();

        $response = $this->getJson('/api/auto-ecoles');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function createAutoEcole()
    {
        $this->createAuthenticatedUser();

        $data = [
            'nom' => 'Auto-école Test',
            'adresse' => 'Rue Test',
            'responsable' => 'M. Faye',
            'telephone' => '770112233',
            'email' => 'test@autoecole.com',
            'statut' => true,
        ];

        $response = $this->postJson('/api/auto-ecoles', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'nom',
                'adresse',
                'responsable',
                'telephone',
                'email',
                'statut',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('auto_ecoles', [
            'nom' => 'Auto-école Test',
            'responsable' => 'M. Faye'
        ]);
    }

    #[Test]
    public function updateAutoEcole()
    {
        $this->createAuthenticatedUser();
        $autoEcole = AutoEcole::factory()->create();

        $newData = [
            'nom' => 'Nouvelle Auto-école',
            'adresse' => $autoEcole->adresse,
            'responsable' => 'Mme Diop',
            'telephone' => $autoEcole->telephone,
            'email' => $autoEcole->email,
            'statut' => $autoEcole->statut
        ];

        $response = $this->putJson("/api/auto-ecoles/{$autoEcole->id}", $newData);

        $response->assertStatus(200)
            ->assertJsonFragment(['nom' => 'Nouvelle Auto-école', 'responsable' => 'Mme Diop']);

        $this->assertDatabaseHas('auto_ecoles', [
            'nom' => 'Nouvelle Auto-école',
            'responsable' => 'Mme Diop'
        ]);
    }

    #[Test]
    public function deleteAutoEcole()
    {
        $this->createAuthenticatedUser();
        $autoEcole = AutoEcole::factory()->create();

        $response = $this->deleteJson("/api/auto-ecoles/{$autoEcole->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Auto-école supprimée avec succès']);

        $this->assertDatabaseMissing('auto_ecoles', ['id' => $autoEcole->id]);
    }
}
