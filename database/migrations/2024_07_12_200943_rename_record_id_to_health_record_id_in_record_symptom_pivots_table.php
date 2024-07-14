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
        Schema::table('record_symptom_pivots', function (Blueprint $table) {
            $table->renameColumn('record_id', 'health_record_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('record_symptom_pivots', function (Blueprint $table) {
            $table->renameColumn('health_record_id', 'record_id');

        });
    }
};
