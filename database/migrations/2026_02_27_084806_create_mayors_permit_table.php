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
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');

            $table->string('health_card')->default(0);
            $table->string('nbi_or_police_clearance')->default(0);
            $table->string('cedula')->default(0);
            $table->string('referral_letter')->default(0);
            
            
            $table->string('peso_id_no');
            $table->string('employers_name_or_address');
            $table->string('community_tax_no');
            $table->date('permit_issued_on');
            $table->string('permit_issued_in');
            $table->string('paid_under_official_receipt');
            $table->date('permit_date');
            $table->date('expires_on');
            $table->string('permit_doc_stamp_control_no');
            $table->string('permit_gor_serial_no');
            $table->date('permit_date_of_payment');


            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_permits');
    }
};
