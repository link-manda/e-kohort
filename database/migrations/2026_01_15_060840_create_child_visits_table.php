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
        Schema::create('child_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->dateTime('visit_date'); // Tanggal & Jam Pendaftaran
            $table->integer('age_month'); // Usia bulan saat kunjungan

            // Anamnesa
            $table->text('complaint')->nullable(); // Keluhan Utama

            // Pemeriksaan Fisik (Vital Signs)
            $table->float('weight')->nullable(); // BB (kg)
            $table->float('height')->nullable(); // TB (cm)
            $table->float('temperature')->nullable(); // Suhu (Celsius) - CRITICAL untuk validasi demam
            $table->integer('heart_rate')->nullable(); // Nadi (bpm)
            $table->integer('respiratory_rate')->nullable(); // Nafas (per menit)
            $table->float('head_circumference')->nullable(); // Lingkar Kepala (cm)
            $table->text('development_notes')->nullable(); // Catatan Tumbuh Kembang

            // Diagnosa
            $table->string('icd_code')->nullable(); // Misal "Z24.0"
            $table->string('diagnosis_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_visits');
    }
};
