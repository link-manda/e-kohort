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
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Kode vaksin: HB0, BCG, POLIO_1, dll');
            $table->string('name')->comment('Nama lengkap vaksin');
            $table->text('description')->nullable()->comment('Deskripsi vaksin');
            $table->unsignedInteger('min_age_months')->default(0)->comment('Usia minimal pemberian (bulan)');
            $table->unsignedInteger('max_age_months')->default(24)->comment('Usia maksimal pemberian (bulan)');
            $table->integer('sort_order')->default(0)->comment('Urutan tampilan');
            $table->boolean('is_active')->default(true)->comment('Status aktif vaksin');
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
