<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SchoolManagement\Paiement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class RelancerPaiementsEnRetardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testPaiementEnRetardEstRelance()
    {
        // 🕓 Fixe la date pour éviter les incohérences
        Carbon::setTestNow('2025-04-07 10:00:00');

        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_retard',
            'derniere_relance' => null,
        ]);

        Artisan::call('paiements:relancer-retards');

        $paiement->refresh();
        $this->assertNotNull($paiement->derniere_relance);
        $this->assertEquals(Carbon::now()->toDateString(), $paiement->derniere_relance->toDateString());

        // 🔁 Reset de l’horloge
        Carbon::setTestNow();
    }

    #[Test]
    public function testPaiementDejaRelanceAujourdHuiNestPasRelance()
    {
        Carbon::setTestNow('2025-04-07 10:00:00');

        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_retard',
            'derniere_relance' => Carbon::now(),
        ]);

        Artisan::call('paiements:relancer-retards');

        $paiement->refresh();
        $this->assertEquals(Carbon::now()->toDateString(), $paiement->derniere_relance->toDateString());

        Carbon::setTestNow();
    }
}
