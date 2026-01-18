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
        Schema::create('icd10_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode ICD-10: Z23, Z24.0, dll');
            $table->string('name')->comment('Nama diagnosa dalam bahasa Inggris');
            $table->text('description')->comment('Deskripsi dalam bahasa Indonesia');
            $table->string('category', 50)->default('general')->comment('Kategori: immunization, wellness, dll');
            $table->json('keywords')->nullable()->comment('Keywords untuk pencarian');
            $table->boolean('is_active')->default(true)->comment('Status aktif kode');
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icd10_codes');
    }
};
