<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\SchoolManagement\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    public function test_peut_creer_un_paiement()
    {
        $autoEcole = AutoEcole::factory()->create();

        $data = [
            'auto_ecole_id' => $autoEcole->id,
            'montant' => 10000,
            'motif' => 'abonnement', // ✅ validé
            'statut' => 'en_attente', // ✅ validé
            'methode_paiement' => 'orange_money',
            'reference' => 'OM123456',
            'payable_jusqua' => now()->addDays(7)->toDateString(),
        ];

        $response = $this->postJson('/api/school-management/paiements', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['motif' => 'abonnement']);
    }

    public function test_peut_lister_les_paiements()
    {
        Paiement::factory()->count(3)->create();
        $response = $this->getJson('/api/school-management/paiements');

        // ✅ on suppose que la réponse retourne directement un tableau
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_peut_afficher_un_paiement()
    {
        $paiement = Paiement::factory()->create();
        $response = $this->getJson("/api/school-management/paiements/{$paiement->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $paiement->id]);
    }

    public function test_peut_mettre_a_jour_un_paiement()
    {
        $paiement = Paiement::factory()->create();

        $updateData = [
            'montant' => 15000,
            'statut' => 'en_retard', // ✅ statut valide
        ];

        $response = $this->putJson("/api/school-management/paiements/{$paiement->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['montant' => 15000]);
    }

    public function test_peut_supprimer_un_paiement()
    {
        $paiement = Paiement::factory()->create();
        $response = $this->deleteJson("/api/school-management/paiements/{$paiement->id}");

        $response->assertStatus(204); // ✅ doit correspondre au `noContent()` dans le contrôleur

        $this->assertDatabaseMissing('paiements', ['id' => $paiement->id]);
    }
}
