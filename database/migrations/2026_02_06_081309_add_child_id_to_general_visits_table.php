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
            // Drop foreign key only if it exists (check via INFORMATION_SCHEMA)
            $database = DB::connection()->getDatabaseName();
            $foreignKeyExists = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                  AND TABLE_NAME = 'general_visits'
                  AND COLUMN_NAME = 'patient_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                LIMIT 1
            ", [$database]);

            if (!empty($foreignKeyExists)) {
                $constraintName = $foreignKeyExists[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `general_visits` DROP FOREIGN KEY `{$constraintName}`");
            }

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
        // Drop child_id foreign key only if it exists
        if (DB::getDriverName() === 'mysql') {
            $database = DB::connection()->getDatabaseName();
            $fk = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                  AND TABLE_NAME = 'general_visits'
                  AND COLUMN_NAME = 'child_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                LIMIT 1
            ", [$database]);

            if (!empty($fk)) {
                DB::statement("ALTER TABLE `general_visits` DROP FOREIGN KEY `{$fk[0]->CONSTRAINT_NAME}`");
            }
        }

        Schema::table('general_visits', function (Blueprint $table) {
            $table->dropColumn('child_id');
        });

        // Note: Reverting patient_id to required would need manual handling
    }
};
