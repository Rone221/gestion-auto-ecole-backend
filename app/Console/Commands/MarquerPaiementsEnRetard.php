<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarquerPaiementsEnRetard extends Command
{
    protected $signature = 'paiements:marquer-en-retard';
    protected $description = 'Marque automatiquement les paiements en attente comme en retard si la date est dépassée.';

    public function handle(): void
    {
        $now = Carbon::now();

        $paiementsEnRetard = Paiement::where('statut', 'en_attente')
            ->whereDate('payable_jusqua', '<', $now)
            ->get();

        if ($paiementsEnRetard->isEmpty()) {
            $this->info("✅ Aucun paiement à marquer en retard.");
            return;
        }

        foreach ($paiementsEnRetard as $paiement) {
            $paiement->update(['statut' => 'en_retard']);
            Log::info("⚠️ Paiement ID {$paiement->id} marqué comme en retard.");
        }

        $this->info("✅ {$paiementsEnRetard->count()} paiements marqués comme en retard.");
    }
}
    
