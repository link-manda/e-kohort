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
        Schema::create('anc_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->onDelete('cascade')->comment('Relasi ke tabel pregnancies');
            $table->date('visit_date')->comment('Tanggal Kunjungan');
            $table->integer('trimester')->comment('Trimester: 1, 2, 3');
            $table->enum('visit_code', ['K1', 'K2', 'K3', 'K4', 'K5', 'K6'])->comment('Kode Kunjungan ANC');
            $table->integer('gestational_age')->comment('Usia Kehamilan dalam Minggu');

            // Data Fisik & Vital Signs
            $table->decimal('weight', 5, 2)->nullable()->comment('Berat Badan (kg)');
            $table->decimal('height', 5, 2)->nullable()->comment('Tinggi Badan (cm)');
            $table->decimal('lila', 4, 1)->nullable()->comment('Lingkar Lengan Atas (cm) - KEK jika <23.5');
            $table->integer('systolic')->nullable()->comment('Tekanan Darah Sistolik');
            $table->integer('diastolic')->nullable()->comment('Tekanan Darah Diastolik');
            $table->decimal('map_score', 5, 2)->nullable()->comment('Mean Arterial Pressure - Dihitung Otomatis');
            $table->integer('tfu')->nullable()->comment('Tinggi Fundus Uteri (cm)');
            $table->integer('djj')->nullable()->comment('Detak Jantung Janin (bpm)');

            // Lab Results & Triple Eliminasi
            $table->decimal('hb', 4, 1)->nullable()->comment('Hemoglobin (g/dL) - Anemia jika <11');
            $table->enum('protein_urine', ['Negatif', '+1', '+2', '+3'])->nullable()->comment('Protein Urine');
            $table->enum('hiv_status', ['NR', 'R', 'Unchecked'])->default('Unchecked')->comment('HIV: Non-Reaktif/Reaktif');
            $table->enum('syphilis_status', ['NR', 'R', 'Unchecked'])->default('Unchecked')->comment('Sifilis');
            $table->enum('hbsag_status', ['NR', 'R', 'Unchecked'])->default('Unchecked')->comment('Hepatitis B');

            // Tindakan & Analisa
            $table->enum('tt_immunization', ['T1', 'T2', 'T3', 'T4', 'T5'])->nullable()->comment('Status Imunisasi TT');
            $table->integer('fe_tablets')->nullable()->comment('Jumlah Tablet Tambah Darah yang diberikan');
            $table->enum('risk_category', ['Rendah', 'Tinggi', 'Ekstrem'])->default('Rendah')->comment('Kategori Risiko');
            $table->text('diagnosis')->nullable()->comment('Diagnosis & Catatan Bidan');
            $table->string('referral_target')->nullable()->comment('Tujuan Rujukan (RS/Puskesmas)');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anc_visits');
    }
};
