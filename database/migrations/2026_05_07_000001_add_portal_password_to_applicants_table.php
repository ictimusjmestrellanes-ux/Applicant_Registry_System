<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('portal_password')->nullable()->after('applicant_code');
        });

        DB::table('applicants')
            ->select('id', 'applicant_code')
            ->orderBy('id')
            ->get()
            ->each(function ($applicant) {
                $applicantCode = trim((string) $applicant->applicant_code);

                if ($applicantCode === '') {
                    return;
                }

                DB::table('applicants')
                    ->where('id', $applicant->id)
                    ->update([
                        'portal_password' => Hash::make($applicantCode),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('portal_password');
        });
    }
};
