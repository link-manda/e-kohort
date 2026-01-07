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
        Schema::table('anc_visits', function (Blueprint $table) {
            $table->text('deleted_reason')->nullable()->after('deleted_at')->comment('Alasan penghapusan visit');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_reason')->comment('ID user yang menghapus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anc_visits', function (Blueprint $table) {
            $table->dropColumn(['deleted_reason', 'deleted_by']);
        });
    }
};
