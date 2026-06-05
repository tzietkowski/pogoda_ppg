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
        Schema::create('weather_condition_logs', function (Blueprint $table) {
            $table->id();

            $table->string('status');
            $table->boolean('is_safe_to_fly');
            $table->decimal('average_wind_ms', 4, 1);
            $table->string('warning')->nullable();
            $table->json('details');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_condition_logs');
    }
};
