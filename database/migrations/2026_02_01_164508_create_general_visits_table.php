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
        Schema::create('general_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->dateTime('visit_date');

            // Subjective
            $table->text('complaint')->comment('Keluhan Utama');

            // Objective - Vital Signs
            $table->integer('systolic')->nullable()->comment('Sistole (mmHg)');
            $table->integer('diastolic')->nullable()->comment('Diastole (mmHg)');
            $table->decimal('temperature', 4, 1)->nullable()->comment('Suhu Tubuh (C)');
            $table->decimal('weight', 5, 2)->nullable()->comment('Berat Badan (kg)');
            $table->decimal('height', 5, 2)->nullable()->comment('Tinggi Badan (cm)');

            // Objective - Physical
            $table->text('physical_exam')->nullable()->comment('Pemeriksaan Fisik');

            // Assessment
            $table->string('diagnosis')->comment('Diagnosa');
            $table->string('icd10_code')->nullable()->comment('Kode ICD-10');

            // Plan
            $table->text('therapy')->comment('Resep Obat / Tindakan');
            $table->enum('status', ['Pulang', 'Rujuk', 'Rawat Inap']);
            $table->enum('payment_method', ['Umum', 'BPJS']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_visits');
    }
};
