<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mayors_permits', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_permits', 'disapproval_reason')) {
                $table->text('disapproval_reason')
                    ->nullable()
                    ->after('approval_status');
            }
        });

        Schema::table('mayors_clearances', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_clearances', 'disapproval_reason')) {
                $table->text('disapproval_reason')
                    ->nullable()
                    ->after('approval_status');
            }
        });

        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (! Schema::hasColumn('mayors_referrals', 'disapproval_reason')) {
                $table->text('disapproval_reason')
                    ->nullable()
                    ->after('approval_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mayors_permits', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_permits', 'disapproval_reason')) {
                $table->dropColumn('disapproval_reason');
            }
        });

        Schema::table('mayors_clearances', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_clearances', 'disapproval_reason')) {
                $table->dropColumn('disapproval_reason');
            }
        });

        Schema::table('mayors_referrals', function (Blueprint $table) {
            if (Schema::hasColumn('mayors_referrals', 'disapproval_reason')) {
                $table->dropColumn('disapproval_reason');
            }
        });
    }
};
