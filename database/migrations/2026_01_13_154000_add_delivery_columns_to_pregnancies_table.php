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
        Schema::table('pregnancies', function (Blueprint $table) {
            $table->dateTime('delivery_date')->nullable()->comment('Waktu Lahir');
            $table->enum('delivery_method', ['Normal', 'Caesar/Sectio', 'Vakum'])->nullable()->comment('Cara Persalinan');
            $table->string('birth_attendant')->nullable()->comment('Penolong Persalinan: Bidan/Dokter');
            $table->string('place_of_birth')->nullable()->comment('Tempat Lahir');
            $table->enum('outcome', ['Hidup', 'Meninggal', 'Abortus'])->nullable()->comment('Kondisi Bayi');
            $table->enum('baby_gender', ['L', 'P'])->nullable()->comment('Jenis Kelamin Bayi: L=Laki-laki, P=Perempuan');
            $table->text('complications')->nullable()->comment('Komplikasi Persalinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pregnancies', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_date',
                'delivery_method',
                'birth_attendant',
                'place_of_birth',
                'outcome',
                'baby_gender',
                'complications'
            ]);
        });
    }
};
