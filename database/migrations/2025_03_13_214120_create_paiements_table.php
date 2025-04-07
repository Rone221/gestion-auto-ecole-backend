<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();

            // 🔗 Clé étrangère vers l’auto-école
            $table->foreignId('auto_ecole_id')
                ->constrained('auto_ecoles')
                ->onDelete('cascade');

            // 💰 Informations du paiement
            $table->decimal('montant', 10, 2);
            $table->enum('motif', ['abonnement', 'pénalité', 'autre'])->default('abonnement');
            $table->enum('statut', ['en_attente', 'réglé', 'en_retard', 'échoué'])->default('en_attente');

            // 🧾 Méthode et infos complémentaires
            $table->string('methode_paiement')->nullable(); // OM, Wave, Espèces, etc.
            $table->string('reference')->nullable();        // Référence transactionnelle
            $table->date('payable_jusqua')->nullable();      // Date limite de paiement

            $table->timestamps();
            $table->timestamp('derniere_relance')->nullable();

            

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
