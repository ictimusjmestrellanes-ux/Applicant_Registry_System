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
        Schema::table('mayors_permits', function (Blueprint $table) {
            $table->timestamp('expiry_reminder_sent_at')->nullable()->after('expires_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mayors_permits', function (Blueprint $table) {
            $table->dropColumn('expiry_reminder_sent_at');
        });
    }
};
