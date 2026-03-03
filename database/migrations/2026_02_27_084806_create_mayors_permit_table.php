<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');

            $table->boolean('health_card')->default(0);
            $table->boolean('nbi_or_police_clearance')->default(0);
            $table->boolean('cedula')->default(0);
            $table->boolean('referral_letter')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayors_permits');
    }
};
