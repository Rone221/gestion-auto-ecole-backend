<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Abonnement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckAbonnementExpiration extends Command
{
    protected $signature = 'abonnements:check-expiration';
    protected $description = 'Vérifier et mettre à jour les abonnements expirés';

    public function handle()
    {
        $today = Carbon::now();

        // 🔎 Récupérer les abonnements expirés
        $expiredAbonnements = Abonnement::where('date_fin', '<', $today)
            ->where('statut', true)
            ->get();

        if ($expiredAbonnements->isNotEmpty()) {
            foreach ($expiredAbonnements as $abonnement) {
                Log::info("🔴 Expiration détectée : Auto-école ID {$abonnement->auto_ecole_id} | Statut AVANT : {$abonnement->statut}");

                // 🔄 Forcer la mise à jour en passant par Eloquent
                $abonnement->update(['statut' => false]);

                // 🔄 Recharger l'objet pour assurer la mise à jour correcte
                $abonnement->refresh();

                Log::info("🟢 Mise à jour effectuée : Auto-école ID {$abonnement->auto_ecole_id} | Statut APRÈS : {$abonnement->statut}");
            }

            $this->info("✅ {$expiredAbonnements->count()} abonnements expirés ont été mis à jour.");
        } else {
            $this->info("✅ Aucun abonnement expiré trouvé.");
        }
    }



        

    
}
