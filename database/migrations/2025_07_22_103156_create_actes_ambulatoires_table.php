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
        Schema::create('actes_ambulatoires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->string('code_acte')->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('cout', 10, 2);
            $table->decimal('taux_couverture', 5, 2)->default(0);
            $table->decimal('montant_patient', 10, 2);
            $table->decimal('montant_rembourse', 10, 2);
            $table->string('statut')->default('en_attente');
            $table->dateTime('date_realisation');
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actes_ambulatoires');
    }
};
