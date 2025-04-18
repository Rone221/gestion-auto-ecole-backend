<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SchoolManagement\Paiement;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class MarquerPaiementsEnRetardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testPaiementEnRetardEstBienMarque()
    {
        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_attente',
            'payable_jusqua' => now()->subDays(2),
        ]);

        Artisan::call('paiements:marquer-en-retard');

        $paiement->refresh();
        $this->assertEquals('en_retard', $paiement->statut);
    }

    #[Test]
    public function testAucunPaiementNonEchuNEstModifie()
    {
        $autoEcole = AutoEcole::factory()->create();

        $paiement = Paiement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'en_attente',
            'payable_jusqua' => now()->addDay(),
        ]);

        Artisan::call('paiements:marquer-en-retard');

        $paiement->refresh();
        $this->assertEquals('en_attente', $paiement->statut);
    }
}

