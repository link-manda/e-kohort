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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('nik', 16)->nullable()->unique(); // Bayi baru lahir belum punya NIK
            $table->string('no_rm')->unique(); // Nomor Rekam Medis Anak, misal: "CH-2024-001"
            $table->string('name');
            $table->enum('gender', ['L', 'P']); // L = Laki-laki, P = Perempuan
            $table->date('dob'); // Date of Birth
            $table->string('pob')->nullable(); // Place of Birth
            $table->float('birth_weight')->nullable(); // Berat Badan Lahir (gram)
            $table->float('birth_height')->nullable(); // Tinggi Badan Lahir (cm)
            $table->enum('status', ['Hidup', 'Meninggal'])->default('Hidup');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
