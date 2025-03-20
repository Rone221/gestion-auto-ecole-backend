<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AbonnementApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function getAllAbonnements()
    {
        Abonnement::factory(3)->create();

        $response = $this->getJson('/api/abonnements');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function createAbonnement()
    {
        $autoEcole = AutoEcole::factory()->create();
        $data = [
            'auto_ecole_id' => $autoEcole->id,
            'type' => 'Annuel',
            'montant' => 50000,
            'date_debut' => now()->toDateString(),
            'date_fin' => now()->addYear()->toDateString(),
            'statut' => true,
        ];

        $response = $this->postJson('/api/abonnements', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('abonnements', ['auto_ecole_id' => $autoEcole->id, 'statut' => true]);
    }
}

