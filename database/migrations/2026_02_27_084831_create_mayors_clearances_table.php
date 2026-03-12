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

            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();

            $table->string('prosecutor_clearance')->default(false);
            $table->string('mtc_clearance')->default(false);
            $table->string('rtc_clearance')->default(false);
            $table->string('nbi_clearance')->default(false);
            $table->string('barangay_clearance')->default(false);
    
            
            
            $table->string('clearance_or_no')->nullable();
            $table->date('clearance_issued_on')->nullable();
            $table->string('clearance_issued_in')->nullable();
            $table->string('clearance_peso_control_no')->nullable();
            $table->string('clearance_doc_stamp_control_no')->nullable();
            $table->date('clearance_date_of_payment')->nullable();
            $table->string('clearance_hired_company')->nullable();

            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_clearances');
    }
};
