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
            // Make nik nullable and remove unique constraint
            $table->string('nik', 16)->nullable()->change();

            // Make phone required (remove nullable)
            $table->string('phone', 15)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Revert nik to not nullable and add unique constraint back
            $table->string('nik', 16)->nullable(false)->unique()->change();

            // Revert phone to nullable
            $table->string('phone', 15)->nullable()->change();
        });
    }
};
