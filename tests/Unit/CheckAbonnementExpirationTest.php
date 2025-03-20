<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class CheckAbonnementExpirationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testCheckAbonnementExpirationCommand()
    {
        $autoEcole = AutoEcole::factory()->create();
        $abonnement = Abonnement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => true,
            'date_fin' => now()->subDay(), // Expiré
        ]);

        // 🔥 Exécuter la commande artisan
        Artisan::call('abonnements:check-expiration');

        // 🔄 Attendre un court instant pour la mise à jour
        sleep(1);

        // 🔄 Vérification explicite depuis la base de données
        $abonnement = Abonnement::where('id', $abonnement->id)->first();

        // ✅ Vérifier que le statut est bien `false`
        $this->assertNotNull($abonnement, "❌ L'abonnement n'a pas été trouvé !");
        $this->assertFalse((bool) $abonnement->statut, "❌ Le statut de l'abonnement n'a pas été mis à jour !");
    }



}
