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
        Schema::create('delivery_records', function (Blueprint $table) {
            $table->id();

            // Relasi ke Pregnancies (One-to-One)
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->onDelete('cascade')->comment('Relasi ke tabel pregnancies');

            // Data Persalinan Umum
            $table->dateTime('delivery_date_time')->comment('Tanggal & Jam Lahir (PENTING untuk Surat Keterangan Lahir)');
            $table->integer('gestational_age')->comment('Umur Kehamilan (Minggu) saat lahir');
            $table->string('birth_attendant')->comment('Penolong: Bidan/Dokter/dll');
            $table->string('place_of_birth')->comment('Tempat Lahir: Klinik/Rumah/dll');

            // Data Ibu - Kala I & II
            $table->string('duration_first_stage')->nullable()->comment('Lama Kala I (dalam jam)');
            $table->string('duration_second_stage')->nullable()->comment('Lama Kala II (dalam menit)');
            $table->enum('delivery_method', [
                'Spontan Belakang Kepala',
                'Sungsang',
                'Vakum',
                'Sectio Caesarea'
            ])->comment('Cara Persalinan');

            // Data Ibu - Kala III (Plasenta)
            $table->enum('placenta_delivery', ['Spontan', 'Manual', 'Sisa'])->default('Spontan')->comment('Cara Lahir Plasenta');

            // Data Ibu - Jalan Lahir
            $table->enum('perineum_rupture', [
                'Utuh',
                'Derajat 1',
                'Derajat 2',
                'Derajat 3',
                'Derajat 4',
                'Episiotomi'
            ])->default('Utuh')->comment('Keadaan Perineum');

            // Data Ibu - Kala IV
            $table->integer('bleeding_amount')->nullable()->comment('Estimasi Perdarahan (ml)');
            $table->string('blood_pressure')->nullable()->comment('Tensi Pasca Salin (Kala IV)');

            // Data Bayi - Identitas
            $table->string('baby_name')->nullable()->comment('Nama Bayi (misal: By. Ny. X)');
            $table->enum('gender', ['L', 'P'])->comment('Jenis Kelamin: L=Laki-laki, P=Perempuan');

            // Data Bayi - Antropometri
            $table->float('birth_weight', 8, 2)->comment('Berat Badan Lahir (gram)');
            $table->float('birth_length', 8, 2)->comment('Panjang Badan Lahir (cm)');
            $table->float('head_circumference', 8, 2)->nullable()->comment('Lingkar Kepala (cm)');

            // Data Bayi - Kondisi Lahir
            $table->integer('apgar_score_1')->nullable()->comment('APGAR Score Menit ke-1');
            $table->integer('apgar_score_5')->nullable()->comment('APGAR Score Menit ke-5');
            $table->enum('condition', ['Hidup', 'Meninggal', 'Asfiksia'])->default('Hidup')->comment('Kondisi Bayi Saat Lahir');
            $table->text('congenital_defect')->nullable()->comment('Kelainan Bawaan (jika ada)');

            // Manajemen Bayi Baru Lahir (Checklist)
            $table->boolean('imd_initiated')->default(false)->comment('Inisiasi Menyusu Dini < 1 Jam');
            $table->boolean('vit_k_given')->default(false)->comment('Injeksi Vitamin K1 Diberikan');
            $table->boolean('eye_ointment_given')->default(false)->comment('Salep Mata Diberikan');
            $table->boolean('hb0_given')->default(false)->comment('Imunisasi Hepatitis B0 Diberikan');

            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa query
            $table->index('pregnancy_id');
            $table->index('delivery_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_records');
    }
};
