<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Enhancement: Upgrade General Visits dengan standar E-Puskesmas
     * - Anamnesa lengkap (Kesadaran, Status Emergency)
     * - Skrining PTM (Gaya Hidup)
     * - Riwayat Kesehatan (Alergi, Penyakit Dahulu)
     * - Pemeriksaan Fisik Head-to-Toe (JSON)
     * - Vital Sign tambahan (Nadi, RR, Lingkar Perut, IMT)
     */
    public function up(): void
    {
        Schema::table('general_visits', function (Blueprint $table) {
            // ========================================
            // A. ANAMNESA & VITAL SIGN TAMBAHAN
            // ========================================
            $table->enum('consciousness', [
                'Compos Mentis',
                'Somnolen',
                'Sopor',
                'Koma'
            ])->default('Compos Mentis')->after('complaint')->comment('Kesadaran');

            $table->boolean('is_emergency')->default(false)->after('consciousness')->comment('Status Gawat Darurat');

            // Vital Sign baru (after height)
            $table->decimal('waist_circumference', 5, 2)->nullable()->after('height')->comment('Lingkar Perut (cm) - Screening Obesitas Sentral');
            $table->decimal('bmi', 5, 2)->nullable()->after('waist_circumference')->comment('IMT (Body Mass Index) - Auto Calculate');
            $table->integer('respiratory_rate')->nullable()->after('temperature')->comment('Respiratory Rate - RR (x/menit)');
            $table->integer('heart_rate')->nullable()->after('respiratory_rate')->comment('Nadi/Heart Rate (x/menit)');

            // ========================================
            // B. RIWAYAT KESEHATAN (Penting untuk Alert)
            // ========================================
            $table->text('allergies')->nullable()->after('complaint')->comment('Riwayat Alergi (Obat/Makanan/Lainnya) - TEXT Format');
            $table->text('medical_history')->nullable()->after('allergies')->comment('Riwayat Penyakit Dahulu (Hipertensi, DM, dll)');

            // ========================================
            // C. GAYA HIDUP (SKRINING PTM)
            // ========================================
            $table->enum('lifestyle_smoking', ['Tidak', 'Ya', 'Jarang'])->default('Tidak')->after('medical_history')->comment('Kebiasaan Merokok');
            $table->boolean('lifestyle_alcohol')->default(false)->after('lifestyle_smoking')->comment('Konsumsi Alkohol');
            $table->enum('lifestyle_activity', ['Aktif', 'Kurang Olahraga'])->default('Aktif')->after('lifestyle_alcohol')->comment('Aktivitas Fisik/Olahraga');
            $table->enum('lifestyle_diet', [
                'Sehat',
                'Kurang Sayur/Buah',
                'Tinggi Gula/Garam/Lemak'
            ])->default('Sehat')->after('lifestyle_activity')->comment('Pola Makan');

            // ========================================
            // D. PEMERIKSAAN FISIK HEAD-TO-TOE (JSON)
            // ========================================
            $table->json('physical_assessment_details')->nullable()->after('physical_exam')->comment('Pemeriksaan Fisik Sistemik (Head-to-Toe) - JSON Format: {kepala, mata, telinga, leher, thorax_jantung, thorax_paru, abdomen, ekstremitas, genitalia}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_visits', function (Blueprint $table) {
            // Drop kolom dalam urutan terbalik
            $table->dropColumn([
                'physical_assessment_details',
                'lifestyle_diet',
                'lifestyle_activity',
                'lifestyle_alcohol',
                'lifestyle_smoking',
                'medical_history',
                'allergies',
                'heart_rate',
                'respiratory_rate',
                'bmi',
                'waist_circumference',
                'is_emergency',
                'consciousness',
            ]);
        });
    }
};
