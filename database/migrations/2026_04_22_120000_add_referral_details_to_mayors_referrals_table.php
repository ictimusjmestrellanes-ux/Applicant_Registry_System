<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mayors_referrals', function (Blueprint $table) {
            $table->json('referral_details')->nullable()->after('ref_city_gov');
        });
    }

    public function down(): void
    {
        Schema::table('mayors_referrals', function (Blueprint $table) {
            $table->dropColumn('referral_details');
        });
    }
};
