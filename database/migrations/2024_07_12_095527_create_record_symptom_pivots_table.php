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
        Schema::create('record_symptom_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained(table: "health_records")->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained(table: "symptoms")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_symptom_pivots');
    }
};
