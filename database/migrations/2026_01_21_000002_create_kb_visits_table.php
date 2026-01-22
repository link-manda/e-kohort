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
        Schema::create('kb_visits', function (Blueprint $table) {
            $table->id();

            // Patient & Visit Info
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->dateTime('visit_date');
            $table->string('no_rm'); // Snapshot from patient

            // Status Kunjungan (Reporting Requirement)
            $table->enum('visit_type', ['Peserta Baru', 'Peserta Lama', 'Ganti Cara']);
            $table->enum('payment_type', ['Umum', 'BPJS']);

            // Pemeriksaan Fisik
            $table->float('weight')->nullable(); // BB in kg
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->text('physical_exam_notes')->nullable(); // For IUD insertion exam notes

            // Metode Kontrasepsi (FK to kb_methods)
            $table->foreignId('kb_method_id')->constrained('kb_methods')->onDelete('cascade');
            $table->string('contraception_brand')->nullable(); // e.g., Triclofem, Cyclofem

            // Tindakan & Medis
            $table->string('icd_code')->nullable(); // e.g., Z30.5
            $table->string('diagnosis')->nullable();
            $table->text('side_effects')->nullable(); // Keluhan/Efek Samping
            $table->text('complications')->nullable();
            $table->boolean('informed_consent')->default(false); // Required for invasive (IUD/Implant)

            // Penjadwalan (Auto-calculated)
            $table->date('next_visit_date')->nullable();

            // Staff
            $table->string('midwife_name');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for reporting queries
            $table->index(['visit_date', 'visit_type']);
            $table->index(['payment_type']);
            $table->index(['kb_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_visits');
    }
};
