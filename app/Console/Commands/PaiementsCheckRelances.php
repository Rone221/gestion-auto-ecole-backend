<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaiementsCheckRelances extends Command
{
    protected $signature = 'paiements:check-relances';
    protected $description = 'Vérifie les paiements en attente ou en retard et envoie des relances';

    public function handle()
    {
        $now = Carbon::now();

        $paiements = Paiement::whereIn('statut', ['en_attente', 'valide', 'en_retard'])
            ->where(function ($query) use ($now) {
                $query->whereNull('derniere_relance')
                      ->orWhere('derniere_relance', '<', $now->subDays(3));
            })
            ->get();

        foreach ($paiements as $paiement) {
            // 👉 Simulation de la relance : Log ou Notification plus tard
            Log::info("📩 Relance automatique pour le paiement ID {$paiement->id} | Auto-école ID {$paiement->auto_ecole_id}");

            // Mise à jour de la date de dernière relance
            $paiement->update([
                'derniere_relance' => $now
            ]);
        }

        $this->info("✅ {$paiements->count()} relances envoyées avec succès.");
    }
}
