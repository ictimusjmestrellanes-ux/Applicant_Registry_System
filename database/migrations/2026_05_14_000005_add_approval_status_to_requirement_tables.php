<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mayors_permits', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_permits', 'approval_status')) {
                $table->string('approval_status')
                    ->default('approved')
                    ->after('applicant_id');
            }
        });

        Schema::table('mayors_clearances', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_clearances', 'approval_status')) {
                $table->string('approval_status')
                    ->default('approved')
                    ->after('applicant_id');
            }
        });

        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_referrals', 'approval_status')) {
                $table->string('approval_status')
                    ->default('approved')
                    ->after('applicant_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mayors_permits', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_permits', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });

        Schema::table('mayors_clearances', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_clearances', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });

        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_referrals', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
