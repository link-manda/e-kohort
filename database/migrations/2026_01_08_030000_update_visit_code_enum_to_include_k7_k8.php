<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum visit_code dari K1-K6 menjadi K1-K8
        DB::statement("ALTER TABLE anc_visits MODIFY visit_code ENUM('K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8') NOT NULL COMMENT 'Kode Kunjungan ANC'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke K1-K6 (data K7 dan K8 akan error jika ada)
        DB::statement("ALTER TABLE anc_visits MODIFY visit_code ENUM('K1', 'K2', 'K3', 'K4', 'K5', 'K6') NOT NULL COMMENT 'Kode Kunjungan ANC'");
    }
};
