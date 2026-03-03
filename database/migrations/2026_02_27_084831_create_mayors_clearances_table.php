<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mayors_clearances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('applicant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('prosecutor_clearance')->default(false);
            $table->boolean('mtc_clearance')->default(false);
            $table->boolean('rtc_clearance')->default(false);
            $table->boolean('nbi_clearance')->default(false);
            $table->boolean('barangay_clearance')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_clearances');
    }
};
