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
        Schema::create('postnatal_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->onDelete('cascade')->comment('Relasi ke tabel pregnancies');
            $table->date('visit_date')->comment('Tanggal Kunjungan');
            $table->enum('visit_code', ['KF1', 'KF2', 'KF3', 'KF4'])->comment('Kode Kunjungan: KF1(6jam-2hari), KF2(3-7hari), KF3(8-28hari), KF4(29-42hari)');

            // Pemeriksaan Fisik Ibu
            $table->integer('td_systolic')->nullable()->comment('Tekanan Darah Sistolik');
            $table->integer('td_diastolic')->nullable()->comment('Tekanan Darah Diastolik');
            $table->decimal('temperature', 4, 1)->nullable()->comment('Suhu Tubuh (°C)');
            $table->enum('lochea', ['Rubra', 'Sanguinolenta', 'Serosa', 'Alba'])->nullable()->comment('Jenis Cairan Nifas');
            $table->string('uterine_involution')->nullable()->comment('Tinggi Fundus Uteri');

            // Tindakan
            $table->boolean('vitamin_a')->default(false)->comment('Pemberian Vitamin A');
            $table->integer('fe_tablets')->default(0)->comment('Jumlah Tablet Fe');
            $table->boolean('complication_check')->default(false)->comment('Pemeriksaan Komplikasi');

            $table->text('conclusion')->nullable()->comment('Kesimpulan: Sehat/Rujuk');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postnatal_visits');
    }
};
