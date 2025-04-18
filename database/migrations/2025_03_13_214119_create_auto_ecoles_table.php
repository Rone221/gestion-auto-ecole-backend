<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('auto_ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('responsable');
            $table->string('nom')->unique();
            $table->string('adresse');
            $table->string('telephone')->unique();
            $table->string('email')->unique();
            $table->boolean('statut')->default(true); // Active par défaut
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auto_ecoles');
    }
};

