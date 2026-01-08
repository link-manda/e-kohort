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
        Schema::table('anc_visits', function (Blueprint $table) {
            // Check and add columns only if they don't exist
            $columns = Schema::getColumnListing('anc_visits');

            // Standar 12T - PENTING
            if (!in_array('anc_12t', $columns)) {
                $table->boolean('anc_12t')->default(false)->after('visit_code');
            }

            // IMT (Indeks Masa Tubuh) / BMI
            if (!in_array('bmi', $columns)) {
                $table->decimal('bmi', 5, 2)->nullable()->after('lila');
            }

            // Letak Janin (Kepala/Sungsang/Lintang)
            if (!in_array('fetal_presentation', $columns)) {
                $table->string('fetal_presentation')->nullable()->after('djj');
            }

            // USG Ya/Tidak - PENTING
            if (!in_array('usg_check', $columns)) {
                $table->boolean('usg_check')->default(false)->after('fetal_presentation');
            }

            // Konseling/KIE
            if (!in_array('counseling_check', $columns)) {
                $table->boolean('counseling_check')->default(false)->after('usg_check');
            }

            // Deteksi Risiko (combines with existing risk_category)
            if (!in_array('risk_level', $columns)) {
                $table->text('risk_level')->nullable()->after('risk_category');
            }

            // Tindak Lanjut
            if (!in_array('follow_up', $columns)) {
                $table->text('follow_up')->nullable()->after('referral_target');
            }

            // Nama Nakes (Tenaga Kesehatan/Bidan)
            if (!in_array('midwife_name', $columns)) {
                $table->string('midwife_name')->nullable()->after('follow_up');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anc_visits', function (Blueprint $table) {
            $columns = Schema::getColumnListing('anc_visits');
            $dropColumns = [];

            if (in_array('anc_12t', $columns)) $dropColumns[] = 'anc_12t';
            if (in_array('bmi', $columns)) $dropColumns[] = 'bmi';
            if (in_array('fetal_presentation', $columns)) $dropColumns[] = 'fetal_presentation';
            if (in_array('usg_check', $columns)) $dropColumns[] = 'usg_check';
            if (in_array('counseling_check', $columns)) $dropColumns[] = 'counseling_check';
            if (in_array('risk_level', $columns)) $dropColumns[] = 'risk_level';
            if (in_array('follow_up', $columns)) $dropColumns[] = 'follow_up';
            if (in_array('midwife_name', $columns)) $dropColumns[] = 'midwife_name';

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
