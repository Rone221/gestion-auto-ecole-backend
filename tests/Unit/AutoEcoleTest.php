<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SchoolManagement\AutoEcole;
use App\Models\SchoolManagement\Abonnement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AutoEcoleTest extends TestCase
{
    use RefreshDatabase; // Nettoie la base après chaque test

    #[Test]
    public function autoEcoleCreation()
    {
        $autoEcole = AutoEcole::factory()->create([
            'nom' => 'Auto-école Dakar',
            'adresse' => '123 Rue Dakar',
            'responsable' => 'M. Ndiaye',
            'telephone' => '770123456',
            'email' => 'contact@ecoledakar.sn',
            'statut' => true,
        ]);

        $this->assertDatabaseHas('auto_ecoles', [
            'nom' => 'Auto-école Dakar',
            'responsable' => 'M. Ndiaye',
        ]);
    }

    #[Test]
    public function autoEcoleDeletion()
    {
        $autoEcole = AutoEcole::factory()->create();
        $autoEcole->delete();

        $this->assertDatabaseMissing('auto_ecoles', ['id' => $autoEcole->id]);
    }

    #[Test]
    public function autoEcoleHasAbonnement()
    {
        $autoEcole = AutoEcole::factory()->create();
        $abonnement = Abonnement::factory()->create(['auto_ecole_id' => $autoEcole->id]);

        $this->assertEquals(1, $autoEcole->abonnements()->count());
    }
}
