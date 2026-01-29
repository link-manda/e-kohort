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
        Schema::table('delivery_records', function (Blueprint $table) {
            // Manajemen Aktif Kala 3 (Active Management of Third Stage of Labour - AMTSL)
            $table->boolean('oxytocin_injection')->default(false)->after('placenta_delivery')
                ->comment('Suntik Oksitosin 10 IU IM dalam 1 menit setelah bayi lahir');

            $table->boolean('controlled_cord_traction')->default(false)->after('oxytocin_injection')
                ->comment('PTT - Peregangan Tali Pusat Terkendali');

            $table->boolean('uterine_massage')->default(false)->after('controlled_cord_traction')
                ->comment('Masase Fundus Uteri setelah plasenta lahir');

            // Kolom tambahan untuk Pemantauan 2 Jam Post Partum
            $table->text('postpartum_monitoring_2h')->nullable()->after('blood_pressure')
                ->comment('Catatan pemantauan 2 jam pertama post partum: TFU, kontraksi, perdarahan, dll');

            // Kolom penyulit/komplikasi persalinan (untuk Register)
            $table->text('complications')->nullable()->after('postpartum_monitoring_2h')
                ->comment('Penyulit/Komplikasi persalinan: Perdarahan, Distosia, dll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_records', function (Blueprint $table) {
            $table->dropColumn([
                'oxytocin_injection',
                'controlled_cord_traction',
                'uterine_massage',
                'postpartum_monitoring_2h',
                'complications',
            ]);
        });
    }
};
