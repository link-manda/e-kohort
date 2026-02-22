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
     * Adds support for registering children whose parents are not registered
     * patients at this clinic (external births).
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // Add external parent fields
            $table->string('parent_name')->nullable()->after('patient_id');
            $table->string('parent_phone', 20)->nullable()->after('parent_name');
            $table->text('parent_address')->nullable()->after('parent_phone');
            $table->enum('birth_location', ['internal', 'external'])->default('internal')->after('parent_address');
        });

        // Make patient_id nullable (requires raw SQL for SQLite compatibility)
        // For MySQL, we need to drop and recreate the foreign key
        if (DB::getDriverName() === 'mysql') {
            // Drop foreign key only if it exists (check via INFORMATION_SCHEMA)
            $database = DB::connection()->getDatabaseName();
            $foreignKeyExists = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                  AND TABLE_NAME = 'children'
                  AND COLUMN_NAME = 'patient_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                LIMIT 1
            ", [$database]);

            if (!empty($foreignKeyExists)) {
                $constraintName = $foreignKeyExists[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `children` DROP FOREIGN KEY `{$constraintName}`");
            }

            // Modify column to be nullable
            Schema::table('children', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->nullable()->change();
            });

            // Re-add foreign key with nullable
            Schema::table('children', function (Blueprint $table) {
                $table->foreign('patient_id')
                    ->references('id')
                    ->on('patients')
                    ->onDelete('set null');
            });
        }

        // Set existing records as 'internal' (they have patient_id)
        DB::table('children')
            ->whereNotNull('patient_id')
            ->update(['birth_location' => 'internal']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove external parent fields
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['parent_name', 'parent_phone', 'parent_address', 'birth_location']);
        });

        // Note: Reverting patient_id to required would need manual handling
        // as it may break data integrity if external children exist
    }
};
