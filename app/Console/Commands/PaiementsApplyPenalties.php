<?php

// app/Console/Commands/PaiementsApplyPenalties.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaiementsApplyPenalties extends Command
{
    protected $signature = 'paiements:apply-penalties';
    protected $description = 'Applique des pénalités aux paiements en retard';

    public function handle()
    {
        $now = Carbon::now();

        $paiementsEnRetard = Paiement::where('statut', 'en_retard')
            ->whereNotNull('payable_jusqua')
            ->where('payable_jusqua', '<', $now)
            ->get();

        $count = 0;

        foreach ($paiementsEnRetard as $paiement) {
            // Vérifier s'il n'existe pas déjà une pénalité associée
            $penaliteExistante = Paiement::where('auto_ecole_id', $paiement->auto_ecole_id)
                ->where('motif', 'pénalité')
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if (!$penaliteExistante) {
                Paiement::create([
                    'auto_ecole_id' => $paiement->auto_ecole_id,
                    'montant' => 5000, // 🔧 Montant fixe ou basé sur règle future
                    'motif' => 'pénalité',
                    'statut' => 'en_attente',
                    'payable_jusqua' => $now->copy()->addDays(7),
                ]);

                Log::warning("⚠️ Pénalité ajoutée pour auto-école ID {$paiement->auto_ecole_id}");
                $count++;
            }
        }

        $this->info("✅ {$count} pénalités ajoutées.");
    }
}
