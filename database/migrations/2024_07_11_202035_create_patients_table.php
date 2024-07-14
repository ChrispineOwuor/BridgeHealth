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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('phoneNumber')->nullable();
            $table->string('residence')->nullable();
            $table->integer('emergencyContact')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_type')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Deceased'])->default('Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
