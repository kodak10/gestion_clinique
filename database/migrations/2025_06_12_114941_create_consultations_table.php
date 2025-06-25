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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('numero_recu')->unique();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('medecin_id')->constrained(); 
            $table->decimal('total', 10, 2);
            $table->decimal('ticket_moderateur', 10, 2);
            $table->decimal('reduction', 10, 2)->default(0);
            $table->decimal('montant_a_paye', 10, 2);
            $table->decimal('montant_paye', 10, 2);
            $table->decimal('reste_a_payer', 10, 2)->default(0);
            $table->string('methode_paiement');
            $table->date('date_consultation');
            $table->string('pdf_path')->nullable(); // Chemin du PDF généré
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
