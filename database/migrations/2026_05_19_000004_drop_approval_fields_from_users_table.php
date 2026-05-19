<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approval_status')) {
                $table->dropColumn('approval_status');
            }

            if (Schema::hasColumn('users', 'disapproval_reason')) {
                $table->dropColumn('disapproval_reason');
            }

            if (Schema::hasColumn('users', 'disapproval_notes')) {
                $table->dropColumn('disapproval_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'approval_status')) {
                $table->string('approval_status')
                    ->default('approved')
                    ->after('auth_provider');
            }

            if (! Schema::hasColumn('users', 'disapproval_reason')) {
                $table->text('disapproval_reason')
                    ->nullable()
                    ->after('approval_status');
            }

            if (! Schema::hasColumn('users', 'disapproval_notes')) {
                $table->text('disapproval_notes')
                    ->nullable()
                    ->after('disapproval_reason');
            }
        });
    }
};
