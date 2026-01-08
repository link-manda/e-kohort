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
        Schema::table('patients', function (Blueprint $table) {
            // Nomor Rekam Medis - PENTING
            $table->string('no_rm')->unique()->nullable()->after('id');

            // Tempat Lahir
            $table->string('pob')->nullable()->after('dob');

            // Pekerjaan Ibu
            $table->string('job')->nullable()->after('pob');

            // Pendidikan Ibu
            $table->string('education')->nullable()->after('job');

            // Pendidikan Suami
            $table->string('husband_education')->nullable()->after('husband_job');

            // Golongan Darah Suami - PENTING
            $table->string('husband_blood_type')->nullable()->after('husband_education');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'no_rm',
                'pob',
                'job',
                'education',
                'husband_education',
                'husband_blood_type',
            ]);
        });
    }
};
