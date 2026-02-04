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
        Schema::table('patients', function (Blueprint $table) {
            // Add category column for filtering patients by type
            // Default 'Umum' untuk backward compatibility
            $table->enum('category', ['Umum', 'Bumil', 'Bayi/Balita', 'Lansia'])
                  ->default('Umum')
                  ->after('gender')
                  ->comment('Kategori Pasien untuk filtering di UI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
