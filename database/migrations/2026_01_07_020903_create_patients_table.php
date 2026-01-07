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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->index()->comment('NIK 16 digit');
            $table->string('no_kk', 16)->nullable()->comment('Nomor Kartu Keluarga');
            $table->string('no_bpjs', 13)->nullable()->comment('Nomor BPJS');
            $table->string('name')->comment('Nama Ibu');
            $table->date('dob')->comment('Date of Birth - Tanggal Lahir');
            $table->text('address')->comment('Alamat Domisili');
            $table->string('phone', 15)->nullable()->comment('Nomor WhatsApp');
            $table->enum('blood_type', ['A', 'B', 'AB', 'O', 'Unknown'])->default('Unknown')->comment('Golongan Darah');
            $table->string('husband_name')->nullable()->comment('Nama Suami');
            $table->string('husband_nik', 16)->nullable()->comment('NIK Suami');
            $table->string('husband_job')->nullable()->comment('Pekerjaan Suami');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
