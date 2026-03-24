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

            //other cities
            $table->string('ref_imus_ocrl')->nullable();
            $table->string('ref_or_no')->nullable();
            $table->string('ref_employer')->nullable();
            $table->string('ref_position')->nullable();
            $table->string('ref_hired_company')->nullable();
            $table->string('ref_company_address')->nullable();

            //peso public service
            $table->string('ref_peso_or_no')->nullable();
            $table->string('ref_recipient')->nullable();
            $table->string('ref_place')->nullable();
            $table->string('ref_ocrl')->nullable();
            $table->string('ref_city_gov')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_referrals');
    }
};
