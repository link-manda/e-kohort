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
        Schema::table('pregnancies', function (Blueprint $table) {
            // BB Sebelum Hamil
            $table->decimal('weight_before', 5, 2)->nullable()->after('pregnancy_gap');

            // TB Ibu (Tinggi Badan) - moved from anc_visits to pregnancies
            $table->decimal('height', 5, 2)->nullable()->after('weight_before');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pregnancies', function (Blueprint $table) {
            $table->dropColumn(['weight_before', 'height']);
        });
    }
};
