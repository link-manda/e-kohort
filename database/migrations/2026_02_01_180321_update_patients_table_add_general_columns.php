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
            // Add gender column if not exists (Generalisasi untuk semua jenis pasien)
            if (!Schema::hasColumn('patients', 'gender')) {
                $table->enum('gender', ['L', 'P'])->default('P')->after('name')
                    ->comment('L=Laki-laki, P=Perempuan');
            }

            // Add responsible_person column if not exists (Penanggung Jawab: bisa Suami/Istri/Ayah/Ibu)
            if (!Schema::hasColumn('patients', 'responsible_person')) {
                $table->string('responsible_person')->nullable()->after('address')
                    ->comment('Nama Penanggung Jawab (Suami/Istri/Orang Tua)');
            }

            // Make husband-specific columns nullable (for flexibility: not all patients are pregnant women)
            $table->string('husband_name')->nullable()->change();
            $table->string('husband_nik', 16)->nullable()->change();
            // Skip husband_phone as column doesn't exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('patients', 'responsible_person')) {
                $table->dropColumn('responsible_person');
            }
        });
    }
};
