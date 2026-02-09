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
        // 1. ANC Visits (Pemeriksaan Kehamilan)
        Schema::table('anc_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('anc_visits', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->nullable()->comment('Biaya Layanan/Jasa');
            }
        });

        // 2. Delivery Records (Persalinan)
        Schema::table('delivery_records', function (Blueprint $table) {
             if (!Schema::hasColumn('delivery_records', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->nullable()->comment('Biaya Layanan/Jasa');
             }
        });

        // 3. Postnatal Visits (Nifas)
        Schema::table('postnatal_visits', function (Blueprint $table) {
             if (!Schema::hasColumn('postnatal_visits', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->nullable()->comment('Biaya Layanan/Jasa');
             }
        });

        // 4. KB Visits (Keluarga Berencana)
        Schema::table('kb_visits', function (Blueprint $table) {
             if (!Schema::hasColumn('kb_visits', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->nullable()->comment('Biaya Layanan/Jasa');
             }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anc_visits', function (Blueprint $table) {
            $table->dropColumn('service_fee');
        });

        Schema::table('delivery_records', function (Blueprint $table) {
            $table->dropColumn('service_fee');
        });

        Schema::table('postnatal_visits', function (Blueprint $table) {
            $table->dropColumn('service_fee');
        });

        Schema::table('kb_visits', function (Blueprint $table) {
            $table->dropColumn('service_fee');
        });
    }
};
