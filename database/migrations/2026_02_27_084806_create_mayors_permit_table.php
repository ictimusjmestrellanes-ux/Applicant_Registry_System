<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mayors_permits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();

            $table->string('health_card')->default(0);
            $table->string('nbi_clearance')->default(0);
            $table->string('police_clearance')->default(0);
            $table->string('cedula')->default(0);
            $table->string('referral_letter')->default(0);

            $table->string('clearance_type')->nullable();

            $table->string('permit_or_no')->nullable();
            $table->string('peso_id_no')->nullable();
            $table->string('employers_name_or_address')->nullable();
            $table->string('community_tax_no')->nullable();

            $table->date('permit_issued_on')->nullable();

            $table->date('permit_date')->nullable();
            $table->date('expires_on')->nullable();

            $table->string('permit_doc_stamp_control_no')->nullable();
            $table->string('permit_gor_serial_no')->nullable();

            $table->date('permit_date_of_payment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_permits');
    }
};
