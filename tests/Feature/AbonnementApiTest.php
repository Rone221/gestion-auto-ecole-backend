<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class AbonnementApiTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $autoEcole;
    protected function setUp(): void
    {
        parent::setUp();

        // Crée le rôle s’il n’existe pas
        Role::firstOrCreate(['name' => 'adminAutoEcole']);

        // Crée une auto-école
        $this->autoEcole = AutoEcole::factory()->create();

        // Crée un user lié à cette auto-école avec le rôle adminAutoEcole
        $this->user = User::factory()->create([
            'auto_ecole_id' => $this->autoEcole->id,
        ]);
        $this->user->assignRole('adminAutoEcole');

        // Authentifie l'utilisateur
        $this->actingAs($this->user, 'sanctum');
    }

    #[Test]
    public function getAllAbonnements()
    {
        Abonnement::factory()->count(3)->create([
            'auto_ecole_id' => $this->autoEcole->id,
        ]);

        $response = $this->getJson('/api/abonnements');

        $response->assertStatus(200)
            ->assertJsonCount(3); // si c'est une pagination
    }

    #[Test]
    public function createAbonnement()
    {
        $data = [
            'auto_ecole_id' => $this->autoEcole->id,
            'type' => 'Annuel',
            'montant' => 50000,
            'date_debut' => now()->toDateString(),
            'date_fin' => now()->addYear()->toDateString(),
            'statut' => true,
        ];

        $response = $this->postJson('/api/abonnements', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('abonnements', [
            'auto_ecole_id' => $this->autoEcole->id,
            'statut' => true,
        ]);
    }
}
