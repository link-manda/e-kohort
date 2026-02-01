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
            $table->enum('gender', ['L', 'P'])->default('P')->after('dob');
            $table->string('responsible_person')->nullable()->after('husband_blood_type')->comment('Penanggung Jawab (Suami/Ayah/Istri/Wali)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['gender', 'responsible_person']);
        });
    }
};
