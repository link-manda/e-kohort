<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('who_standards', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['L', 'P']);
            $table->enum('type', ['BB_U', 'TB_U', 'BB_TB']);
            $table->integer('age_month')->nullable()->comment('Umur dalam bulan');
            $table->float('length_cm', 8, 1)->nullable()->comment('Panjang badan untuk BB/TB');

            // Standar Deviasi WHO
            $table->float('sd_minus_3', 8, 2);
            $table->float('sd_minus_2', 8, 2);
            $table->float('sd_minus_1', 8, 2);
            $table->float('sd_median', 8, 2);
            $table->float('sd_plus_1', 8, 2);
            $table->float('sd_plus_2', 8, 2);
            $table->float('sd_plus_3', 8, 2);

            $table->timestamps();

            // Index untuk performa query
            $table->index(['gender', 'type', 'age_month']);
            $table->index(['gender', 'type', 'length_cm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('who_standards');
    }
};
