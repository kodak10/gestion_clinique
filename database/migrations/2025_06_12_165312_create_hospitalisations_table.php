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
        Schema::create('hospitalisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('medecin_id')->nullable()->constrained()->onDelete('set null');

            $table->decimal('total', 10, 2);
            $table->decimal('ticket_moderateur', 10, 2);
            $table->decimal('reduction', 10, 2)->default(0);
            $table->decimal('montant_a_paye', 10, 2);

            $table->timestamp('date_entree')->useCurrent();
            $table->timestamp('date_sortie')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalisations');
    }
};
