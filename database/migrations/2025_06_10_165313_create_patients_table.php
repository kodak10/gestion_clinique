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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->date('date_naissance');
            $table->string('domicile');
            $table->enum('sexe', ['M', 'F']);
            $table->string('profession')->nullable();
            $table->string('ethnie')->nullable();
            $table->string('religion')->nullable();
            $table->string('groupe_rhesus')->nullable();
            $table->string('electrophorese')->nullable();
            $table->foreignId('assurance_id')->nullable()->constrained();
            $table->integer('taux_couverture')->nullable();
            $table->string('matricule_assurance')->nullable();
            $table->string('contact_urgence')->nullable();
            $table->string('contact_patient');
            $table->string('photo')->nullable();
            $table->string('envoye_par')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
