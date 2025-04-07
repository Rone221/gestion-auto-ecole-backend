<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RelancerPaiementsEnRetard extends Command
{
    protected $signature = 'paiements:relancer-retards';
    protected $description = 'Relance les paiements en retard (une fois par jour).';

    public function handle(): void
    {
        $now = Carbon::now();

        // 🔎 Paiements en retard n’ayant pas été relancés aujourd’hui
        $paiements = Paiement::where('statut', 'en_retard')
            ->where(function ($query) use ($now) {
                $query->whereNull('derniere_relance')
                      ->orWhereDate('derniere_relance', '<', $now->toDateString());
            })
            ->get();

        if ($paiements->isEmpty()) {
            $this->info('✅ Aucun paiement à relancer aujourd’hui.');
            return;
        }

        foreach ($paiements as $paiement) {
            // Simuler l’envoi de relance (email, SMS, notification)
            Log::info("🔁 Relance envoyée pour paiement ID {$paiement->id} | Auto-école ID {$paiement->auto_ecole_id}");

            // ⏱️ Mettre à jour la date de dernière relance
            $paiement->update(['derniere_relance' => $now]);
        }

        $this->info("✅ {$paiements->count()} relances envoyées avec succès.");
    }
}

