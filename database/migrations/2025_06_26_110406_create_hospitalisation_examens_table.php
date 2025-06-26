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
        Schema::create('hospitalisation_examen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospitalisation_id')->constrained();
            $table->foreignId('examen_id')->constrained();
            $table->integer('quantite');
            $table->decimal('prix', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalisation_examens');
    }
};
