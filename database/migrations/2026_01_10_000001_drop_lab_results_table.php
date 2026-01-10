<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drop lab_results table karena data lab sudah terintegrasi di anc_visits.
     * Tabel ini duplikat dan tidak digunakan oleh form/seeder.
     */
    public function up(): void
    {
        Schema::dropIfExists('lab_results');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anc_visit_id')->constrained('anc_visits')->onDelete('cascade');

            // Hemoglobin (g/dL)
            $table->decimal('hb', 4, 2)->nullable();

            // Protein Urine
            $table->string('protein_urine')->nullable();

            // Status HIV
            $table->string('hiv_status')->nullable();

            // Status Sifilis
            $table->string('syphilis_status')->nullable();

            // Status HBsAg
            $table->string('hbsag_status')->nullable();

            // Status Anemia (calculated based on Hb)
            $table->string('anemia_status')->nullable();

            $table->timestamps();
        });
    }
};
