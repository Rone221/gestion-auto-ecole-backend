<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class DisableUnpaidAutoEcolesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function disableUnpaidAutoEcolesCommand()
    {
        // 🏗️ Préparation des données
        $autoEcole = AutoEcole::factory()->create(['statut' => true]);
        $abonnement = Abonnement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'non payé',
            'date_fin' => Carbon::now()->subDay(), // Expiré
        ]);

        // 🛠️ Exécute la commande
        Artisan::call('auto-ecoles:disable-unpaid');

        // 🔄 Rafraîchir les instances depuis la BDD
        $abonnement->refresh();
        $autoEcole->refresh();

        // ✅ Assert corrects
        $this->assertEquals('non payé', $abonnement->statut);
        $this->assertFalse((bool) $autoEcole->statut, "❌ L'auto-école n'a pas été désactivée !");
        $this->assertDatabaseHas('auto_ecoles', [
            'id' => $autoEcole->id,
            'statut' => false
        ]);
    }
}
