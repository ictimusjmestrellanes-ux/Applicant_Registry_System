<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'disapproval_reason')) {
                $table->text('disapproval_reason')->nullable()->after('approval_status');
            }

            if (! Schema::hasColumn('users', 'disapproval_notes')) {
                $table->text('disapproval_notes')->nullable()->after('disapproval_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'disapproval_notes')) {
                $table->dropColumn('disapproval_notes');
            }

            if (Schema::hasColumn('users', 'disapproval_reason')) {
                $table->dropColumn('disapproval_reason');
            }
        });
    }
};
