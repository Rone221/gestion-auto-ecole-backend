<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auto_ecole_id')->constrained('auto_ecoles')->onDelete('cascade');
            $table->string('type'); // Exemple : "Mensuel", "Annuel"
            $table->decimal('montant', 10, 2);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('statut')->default(true); // Actif par défaut
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('abonnements');
    }
};

