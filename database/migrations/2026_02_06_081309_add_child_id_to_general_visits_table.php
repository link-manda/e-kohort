<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds child_id to general_visits table to support universal Poli Umum
     * for all ages (adults from patients table, children from children table).
     */
    public function up(): void
    {
        // Step 1: Add child_id column
        Schema::table('general_visits', function (Blueprint $table) {
            $table->foreignId('child_id')
                ->nullable()
                ->after('patient_id')
                ->constrained('children')
                ->nullOnDelete();
        });

        // Step 2: Make patient_id nullable (for MySQL)
        if (DB::getDriverName() === 'mysql') {
            // Drop foreign key first
            Schema::table('general_visits', function (Blueprint $table) {
                $table->dropForeign(['patient_id']);
            });

            // Modify column to be nullable
            Schema::table('general_visits', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable()->change();
            });

            // Re-add foreign key with nullable support
            Schema::table('general_visits', function (Blueprint $table) {
                $table->foreign('patient_id')
                    ->references('id')
                    ->on('patients')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_visits', function (Blueprint $table) {
            $table->dropForeign(['child_id']);
            $table->dropColumn('child_id');
        });

        // Note: Reverting patient_id to required would need manual handling
    }
};
