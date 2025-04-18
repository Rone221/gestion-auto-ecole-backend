<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolManagement\Abonnement;
use App\Models\SchoolManagement\AutoEcole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DisableUnpaidAutoEcoles extends Command
{
    protected $signature = 'auto-ecoles:disable-unpaid';
    protected $description = 'Désactiver les auto-écoles dont l\'abonnement a expiré';

    public function handle()
    {
        $today = Carbon::now();

        // Trouver les abonnements expirés et désactiver l'auto-école
        $expiredAbonnements = Abonnement::where('date_fin', '<', $today)
            ->where('statut', 'non payé')
            ->pluck('auto_ecole_id');

        AutoEcole::whereIn('id', $expiredAbonnements)->update(['statut' => 0]);

        // Envoyer un email de rappel aux auto-écoles désactivées
        $autoEcoles = AutoEcole::whereIn('id', $expiredAbonnements)->get();
        foreach ($autoEcoles as $autoEcole) {
            Log::info("Désactivation de l'auto-école ID {$autoEcole->id} pour non-paiement.");
            // Ici, on peut ajouter l'envoi d'un email de rappel si la fonction Mail est activée.
        }

        $this->info("Désactivation des auto-écoles non payées terminée.");
    }
}
