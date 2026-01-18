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
        Schema::table('child_visits', function (Blueprint $table) {
            // Status Gizi - Manual selection by healthcare provider
            $table->enum('nutritional_status', [
                'Gizi Buruk',
                'Gizi Kurang',
                'Gizi Baik',
                'Gizi Lebih',
                'Obesitas'
            ])->nullable()->after('development_notes')->comment('Status gizi berdasarkan BB/U');

            // Informed Consent - Legal requirement (wajib dicentang)
            $table->boolean('informed_consent')->default(false)->after('nutritional_status')->comment('Persetujuan tindakan medis');

            // Medication/KIPI Management
            $table->enum('medicine_given', [
                'Parasetamol Drop',
                'Parasetamol Sirup',
                'Tidak Ada',
                'Lainnya'
            ])->nullable()->after('informed_consent')->comment('Obat yang diberikan');

            $table->string('medicine_dosage')->nullable()->after('medicine_given')->comment('Dosis obat (contoh: 3x0.5ml)');

            // Additional notes
            $table->text('notes')->nullable()->after('medicine_dosage')->comment('Keterangan tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_visits', function (Blueprint $table) {
            $table->dropColumn([
                'nutritional_status',
                'informed_consent',
                'medicine_given',
                'medicine_dosage',
                'notes'
            ]);
        });
    }
};
