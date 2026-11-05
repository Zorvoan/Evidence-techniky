<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrace: tabulka "service_records" = servisní zásahy u zařízení.
 */
return new class extends Migration
{
    /**
     * Vytvoření tabulky.
     */
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();                                   // primární klíč

            // Cizí klíč na zařízení. Při smazání zařízení se smaže i jeho servisní historie.
            $table->foreignId('equipment_id')
                ->constrained('equipment')
                ->cascadeOnDelete();

            $table->text('description');                    // popis servisního zásahu
            $table->date('serviced_at');                    // datum servisu
            $table->decimal('cost', 10, 2)->default(0);     // cena zásahu (desetinné číslo, 2 des. místa)
            $table->timestamps();                           // created_at, updated_at
        });
    }

    /**
     * Smazání tabulky (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};
