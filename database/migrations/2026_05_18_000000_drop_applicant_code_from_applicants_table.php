<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'applicant_code')) {
                $table->dropUnique('applicants_applicant_code_unique');
                $table->dropColumn('applicant_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (! Schema::hasColumn('applicants', 'applicant_code')) {
                $table->string('applicant_code')->nullable()->unique()->after('id');
            }
        });
    }
};
