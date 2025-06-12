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
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Médecin ou utilisateur qui enregistre
            $table->date('date_consultation');
            $table->text('motif')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('prescription')->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('ticket_moderateur', 10, 2);
            $table->decimal('reduction', 10, 2)->default(0);
            $table->decimal('montant_paye', 10, 2);
            $table->timestamps();
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
