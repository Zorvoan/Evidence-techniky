<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrace: tabulka "equipment" = evidované firemní nářadí / zařízení.
 */
return new class extends Migration
{
    /**
     * Vytvoření tabulky.
     */
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();                                   // primární klíč
            $table->string('name');                         // název zařízení
            $table->string('inventory_number', 50)->unique(); // evidenční číslo – jedinečné i v DB
            $table->string('status', 20)->default('active'); // stav: active / service / retired
            $table->date('purchased_at');                   // datum nákupu
            $table->timestamps();                           // created_at, updated_at
        });
    }

    /**
     * Smazání tabulky (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
