<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('applicants')
            ->whereRaw('UPPER(city) LIKE ?', ['%BACOOR%'])
            ->whereRaw('UPPER(barangay) = ?', ['P.F. ESPIRITU'])
            ->update([
                'barangay' => 'PANAPAAN',
            ]);
    }

    public function down(): void
    {
        DB::table('applicants')
            ->whereRaw('UPPER(city) LIKE ?', ['%BACOOR%'])
            ->whereRaw('UPPER(barangay) = ?', ['PANAPAAN'])
            ->update([
                'barangay' => 'P.F. ESPIRITU',
            ]);
    }
};
