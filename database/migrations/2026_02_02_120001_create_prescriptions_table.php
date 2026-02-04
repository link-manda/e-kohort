<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel Prescriptions: Riwayat Resep Obat
     * - Relasi dengan general_visits (hasMany)
     * - Input manual nama obat + harga (tanpa master data)
     * - Auto calculate total harga
     * - Support soft deletes untuk koreksi kesalahan input
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_visit_id')->constrained('general_visits')->cascadeOnDelete();

            // ========================================
            // DATA OBAT (Manual Input - No Master)
            // ========================================
            $table->string('medicine_name')->comment('Nama Obat (Manual Input)');
            $table->string('quantity')->comment('Jumlah - misal: 10 tablet, 1 botol, 20 kapsul');
            $table->string('signa')->comment('Aturan Pakai - misal: 3x1 sesudah makan, 2x1 pagi-malam');

            // ========================================
            // HARGA (untuk pencatatan biaya)
            // ========================================
            $table->decimal('unit_price', 10, 2)->comment('Harga per unit/satuan (Rp)');
            $table->integer('quantity_number')->comment('Jumlah dalam angka (untuk kalkulasi total)');
            $table->decimal('total_price', 10, 2)->comment('Total Harga (Auto Calculate: unit_price * quantity_number)');

            // ========================================
            // OPTIONAL: Detail Tambahan
            // ========================================
            $table->string('dosage')->nullable()->comment('Dosis - misal: 500mg, 10ml');
            $table->string('frequency')->nullable()->comment('Frekuensi - misal: 3x sehari, 2x sehari');
            $table->string('duration')->nullable()->comment('Durasi - misal: 5 hari, 1 minggu');
            $table->text('notes')->nullable()->comment('Catatan untuk Apoteker/Pasien');

            $table->timestamps();
            $table->softDeletes()->comment('Untuk koreksi kesalahan input resep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
