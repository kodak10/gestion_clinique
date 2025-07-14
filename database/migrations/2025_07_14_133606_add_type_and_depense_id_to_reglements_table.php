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
        Schema::table('reglements', function (Blueprint $table) {
            $table->foreignId('depense_id')
                  ->nullable()
                  ->constrained('depenses')
                  ->onDelete('cascade')
                  ->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reglements', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère d'abord
            $table->dropForeign(['depense_id']);
            
            // Puis supprimer les colonnes
            $table->dropColumn(['type', 'depense_id']);
        });
    }
};
