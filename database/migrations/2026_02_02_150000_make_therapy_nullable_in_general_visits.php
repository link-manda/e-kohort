<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix: Kolom therapy sekarang optional karena resep obat sudah di tabel prescriptions
     */
    public function up(): void
    {
        Schema::table('general_visits', function (Blueprint $table) {
            $table->text('therapy')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_visits', function (Blueprint $table) {
            $table->text('therapy')->nullable(false)->change();
        });
    }
};
