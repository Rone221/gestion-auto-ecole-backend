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

        // Trouver les abonnements expirés
        $expiredAbonnements = Abonnement::where('date_fin', '<', $today)
            ->where('statut', 'payé')
            ->update(['statut' => 'non payé']);

        // Envoyer un rappel de paiement pour les abonnements qui expirent bientôt (dans 3 jours)
        $expiringSoon = Abonnement::whereBetween('date_fin', [$today, $today->copy()->addDays(3)])
            ->where('statut', 'payé')
            ->get();

        foreach ($expiringSoon as $abonnement) {
            Log::info("Rappel : L'abonnement de l'auto-école ID {$abonnement->auto_ecole_id} expire bientôt.");
            // Ici, on peut ajouter une notification par email/SMS.
        }

        $this->info("Vérification des abonnements expirés terminée.");
    }
}
