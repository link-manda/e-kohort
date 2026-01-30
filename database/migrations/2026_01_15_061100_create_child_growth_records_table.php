<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->date('record_date');
            $table->integer('age_in_months');

            // Anthropometry (Input)
            $table->float('weight', 8, 2)->comment('Berat Badan (kg)');
            $table->float('height', 8, 2)->comment('Tinggi/Panjang Badan (cm)');
            $table->float('head_circumference', 8, 2)->nullable()->comment('Lingkar Kepala (cm)');
            $table->enum('measurement_method', ['Terlentang', 'Berdiri'])->default('Terlentang');

            // Status Gizi (Output Kalkulasi)
            $table->float('zscore_bb_u', 8, 3)->nullable()->comment('Z-Score BB/U');
            $table->enum('status_bb_u', ['Gizi Buruk', 'Kurang', 'Baik', 'Lebih'])->nullable();

            $table->float('zscore_tb_u', 8, 3)->nullable()->comment('Z-Score TB/U');
            $table->enum('status_tb_u', ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'])->nullable();

            $table->float('zscore_bb_tb', 8, 3)->nullable()->comment('Z-Score BB/TB');
            $table->enum('status_bb_tb', ['Gizi Buruk', 'Gizi Kurang', 'Baik', 'Gizi Lebih', 'Obesitas'])->nullable();

            // Intervensi
            $table->enum('vitamin_a', ['Tidak', 'Biru (6-11 bln)', 'Merah (1-5 thn)'])->default('Tidak');
            $table->boolean('deworming_medicine')->default(false);
            $table->boolean('pmt_given')->default(false)->comment('Pemberian Makanan Tambahan');

            $table->text('notes')->nullable();
            $table->string('midwife_name')->nullable();

            $table->timestamps();

            // Index untuk performa query
            $table->index(['child_id', 'record_date']);
            $table->index('record_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_growth_records');
    }
};
