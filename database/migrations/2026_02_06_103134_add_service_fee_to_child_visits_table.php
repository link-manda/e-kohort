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
        Schema::table('child_visits', function (Blueprint $table) {
            // Payment information
            $table->decimal('service_fee', 10, 2)->default(0)->after('notes')->comment('Biaya pelayanan imunisasi');
            $table->enum('payment_method', ['Umum', 'BPJS'])->default('Umum')->after('service_fee')->comment('Metode pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_visits', function (Blueprint $table) {
            $table->dropColumn(['service_fee', 'payment_method']);
        });
    }
};
