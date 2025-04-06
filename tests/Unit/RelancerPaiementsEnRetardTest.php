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
        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_retard',
            'derniere_relance' => null,
        ]);

        Artisan::call('paiements:check-relances');


        $paiement->refresh();
        $this->assertNotNull($paiement->derniere_relance);
        $this->assertEquals(now()->toDateString(), $paiement->derniere_relance->toDateString());
    }

    #[Test]
    public function testPaiementDejaRelanceAujourdHuiNestPasRelance()
    {
        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_retard',
            'derniere_relance' => now(),
        ]);

        Artisan::call('paiements:check-relances');


        $paiement->refresh();
        $this->assertEquals(now()->toDateString(), $paiement->derniere_relance->toDateString());
    }
}
