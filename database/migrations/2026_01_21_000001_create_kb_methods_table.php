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
        Schema::create('kb_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., SUNTIK_1M, IUD_SILVERLINE
            $table->string('name'); // e.g., "Suntik 1 Bulan (Cyclofem)"
            $table->enum('category', ['SUNTIK', 'PIL', 'IMPLANT', 'IUD', 'LAINNYA']);
            $table->boolean('is_hormonal')->default(false); // Critical for hypertension validation
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'is_active']);
            $table->index('is_hormonal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_methods');
    }
};
