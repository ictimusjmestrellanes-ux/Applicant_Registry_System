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
        Schema::create('mayors_clearances_letter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();

            $table->string('or_no')->nullable();
            $table->date('clearance_issued_on')->nullable();
            $table->string('clearance_issued_in')->nullable();
            $table->string('peso_control_no')->nullable();
            $table->string('clearance_doc_stamp_control_no')->nullable();
            $table->string('clearance_gor_control_no')->nullable();
            $table->date('date_of_payment')->nullable();
            $table->string('hired_company')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mayors_clearances_letter');
    }
};
