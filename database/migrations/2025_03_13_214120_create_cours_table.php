<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('type', ['theorique', 'pratique']);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->enum('statut', ['planifie', 'reserve', 'termine', 'annule'])->default('planifie');
            $table->foreignId('moniteur_id')->constrained('users');
            $table->foreignId('eleve_id')->nullable()->constrained('users');
            $table->foreignId('auto_ecole_id')->constrained('auto_ecoles');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
