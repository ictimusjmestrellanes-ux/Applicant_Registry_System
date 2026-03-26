<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_referrals', 'referral_type')) {
                $table->string('referral_type')->nullable()->after('applicant_id');
            }

            if (Schema::hasColumn('mayors_referrals', 'ref_employer') && ! Schema::hasColumn('mayors_referrals', 'ref_employer_name')) {
                $table->renameColumn('ref_employer', 'ref_employer_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_referrals', 'ref_employer_name') && ! Schema::hasColumn('mayors_referrals', 'ref_employer')) {
                $table->renameColumn('ref_employer_name', 'ref_employer');
            }

            if (Schema::hasColumn('mayors_referrals', 'referral_type')) {
                $table->dropColumn('referral_type');
            }
        });
    }
};
