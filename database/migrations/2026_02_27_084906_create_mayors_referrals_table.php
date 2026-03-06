<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mayors_referrals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('applicant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('resume')->default(false);

            $table->string('ref_barangay_clearance')->default(false);
            $table->string('ref_police_clearance')->default(false);
            $table->string('ref_nbi_clearance')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_referrals');
    }
};
