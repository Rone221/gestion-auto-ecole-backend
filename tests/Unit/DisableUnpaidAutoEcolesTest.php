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
        $autoEcole = AutoEcole::factory()->create();
        Abonnement::factory()->create([
            'auto_ecole_id' => $autoEcole->id,
            'statut' => 'non payé',
            'date_fin' => Carbon::now()->subDays(1)
        ]);

        Artisan::call('auto-ecoles:disable-unpaid');

        $this->assertDatabaseHas('auto_ecoles', ['id' => $autoEcole->id, 'statut' => 0]);
    }
}
