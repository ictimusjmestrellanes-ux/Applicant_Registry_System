<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('applicants')
            ->select('id', 'barangay')
            ->whereRaw('UPPER(city) LIKE ?', ['%BACOOR%'])
            ->whereRaw('UPPER(barangay) LIKE ?', ['P.F. ESPIRITU%'])
            ->get();

        foreach ($rows as $row) {
            $barangay = strtoupper(trim((string) $row->barangay));
            $barangay = preg_replace('/^P\.F\. ESPIRITU\b/i', 'PANAPAAN', $barangay) ?? $barangay;

            DB::table('applicants')
                ->where('id', $row->id)
                ->update(['barangay' => $barangay]);
        }
    }

    public function down(): void
    {
        $rows = DB::table('applicants')
            ->select('id', 'barangay')
            ->whereRaw('UPPER(city) LIKE ?', ['%BACOOR%'])
            ->whereRaw('UPPER(barangay) LIKE ?', ['PANAPAAN%'])
            ->get();

        foreach ($rows as $row) {
            $barangay = strtoupper(trim((string) $row->barangay));
            $barangay = preg_replace('/^PANAPAAN\b/i', 'P.F. ESPIRITU', $barangay) ?? $barangay;

            DB::table('applicants')
                ->where('id', $row->id)
                ->update(['barangay' => $barangay]);
        }
    }
};
