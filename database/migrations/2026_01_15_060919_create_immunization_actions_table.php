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
        Schema::create('immunization_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_visit_id')->constrained('child_visits')->onDelete('cascade');
            $table->string('vaccine_type'); // HB0, BCG, Polio 1-4, DPT-HB-Hib 1-3, IPV, MR, dll
            $table->string('batch_number')->nullable(); // No Batch Vaksin
            $table->string('body_part')->nullable(); // Lokasi Suntikan: Paha Kanan/Kiri, Lengan
            $table->string('provider_name')->nullable(); // Nama Nakes yang melakukan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunization_actions');
    }
};
