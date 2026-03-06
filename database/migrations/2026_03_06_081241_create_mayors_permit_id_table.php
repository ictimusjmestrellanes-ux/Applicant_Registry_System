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
        Schema::create('mayors_permit_id', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();

            $table->string('employers_name_or_address')->nullable();
            $table->string('community_tax_no')->nullable();
            $table->date('permit_issued_on')->nullable();
            $table->string('permit_issued_in')->nullable();
            $table->string('paid_under_official_receipt')->nullable();
            $table->date('permit_date')->nullable();
            $table->date('expires_on')->nullable();
            $table->string('permit_doc_stamp_control_no')->nullable();
            $table->string('permit_gor_serial_no')->nullable();
            $table->date('permit_date_of_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mayors_permit_id');
    }
};
