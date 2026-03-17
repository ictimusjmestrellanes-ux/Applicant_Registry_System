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
        Schema::create('applicants', function (Blueprint $table) {
            
            $table->id();
            
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            
            $table->integer('age')->nullable();

            $table->string('contact_no');
            $table->string('gender');

            $table->enum('civil_status', [
                'Single',
                'Married',
                'Widowed',
            ])->nullable();

            $table->string('pwd')->nullable();
            $table->string('four_ps')->nullable();

            $table->string('address_line');
            $table->string('province');
            $table->string('city');
            $table->string('barangay');

            $table->string('educational_attainment')->nullable();

            $table->string('hiring_company')->nullable();
            $table->string('position_hired')->nullable();

            $table->string('first_time_job_seeker')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
