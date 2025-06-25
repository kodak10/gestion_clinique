<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_depense_id')->nullable()->constrained('category_depenses')->onDelete('set null');
            $table->string('numero_recu')->unique();
            $table->string('libelle');
            $table->decimal('montant', 15, 2);
            $table->date('date');
            $table->string('numero_cheque')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
