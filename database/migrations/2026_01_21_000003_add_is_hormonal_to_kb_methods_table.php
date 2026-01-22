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
        if (!Schema::hasColumn('kb_methods', 'is_hormonal')) {
            Schema::table('kb_methods', function (Blueprint $table) {
                $table->boolean('is_hormonal')->default(false)->after('category');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('kb_methods', 'is_hormonal')) {
            Schema::table('kb_methods', function (Blueprint $table) {
                $table->dropColumn('is_hormonal');
            });
        }
    }
};
