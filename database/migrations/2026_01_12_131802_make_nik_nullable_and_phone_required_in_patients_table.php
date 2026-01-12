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
            // Ubah NIK menjadi nullable (tidak wajib)
            $table->string('nik', 16)->nullable()->change();

            // Ubah phone menjadi required (wajib)
            $table->string('phone', 15)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Kembalikan NIK menjadi required
            $table->string('nik', 16)->nullable(false)->change();

            // Kembalikan phone menjadi nullable
            $table->string('phone', 15)->nullable()->change();
        });
    }
};
