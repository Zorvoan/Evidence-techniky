<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // servisni zasahy k zarizeni
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            // kdyz se smaze zarizeni, smaze se i jeho servis
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->text('description');
            $table->date('serviced_at');
            $table->decimal('cost', 10, 2); // cena na 2 desetinna mista
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};
