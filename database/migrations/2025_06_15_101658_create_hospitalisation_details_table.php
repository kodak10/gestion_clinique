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
        Schema::create('hospitalisation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospitalisation_id')->constrained();
            $table->foreignId('frais_hospitalisation_id')->constrained();
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('quantite')->default(1);
            $table->decimal('reduction', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_frais_pharmacies');
    }
};
