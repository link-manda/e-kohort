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
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade')->comment('Relasi ke tabel patients');
            $table->string('gravida', 20)->comment('Status Kehamilan: G1P0A0, dll');
            $table->date('hpht')->comment('Hari Pertama Haid Terakhir');
            $table->date('hpl')->comment('Hari Perkiraan Lahir / Estimated Due Date');
            $table->integer('pregnancy_gap')->nullable()->comment('Jarak kehamilan dalam tahun');
            $table->enum('status', ['Aktif', 'Lahir', 'Abortus'])->default('Aktif')->comment('Status Kehamilan');
            $table->integer('risk_score_initial')->nullable()->comment('Skor Poedji Rochjati');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};
