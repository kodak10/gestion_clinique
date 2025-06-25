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
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospitalisation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('consultation_id')->nullable()->constrained()->cascadeOnDelete();            
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // caissier
            $table->decimal('montant', 10, 2);
            $table->enum('methode_paiement', ['cash', 'mobile_money', 'virement']);
            $table->string('type')->default('entrée'); // ou 'sortie' si un jour tu fais aussi des dépenses
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglements');
    }
};
