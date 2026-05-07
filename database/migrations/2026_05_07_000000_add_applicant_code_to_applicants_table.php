<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('applicant_code')->nullable()->unique()->after('id');
        });

        DB::table('applicants')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function ($applicant) {
                DB::table('applicants')
                    ->where('id', $applicant->id)
                    ->update([
                        'applicant_code' => sprintf('APL-%06d', $applicant->id),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropUnique(['applicant_code']);
            $table->dropColumn('applicant_code');
        });
    }
};
