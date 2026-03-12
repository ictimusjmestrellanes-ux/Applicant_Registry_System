<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Clearance;
use Illuminate\Support\Str;

class ClearanceController extends Controller
{

    public function update(Request $request, $id)
    {

        $applicant = Applicant::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance = Clearance::firstOrNew([
            'applicant_id' => $applicant->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREPARE FILE NAME
        |--------------------------------------------------------------------------
        */

        $fullName = $applicant->last_name . '_' . $applicant->first_name;
        $fullName = Str::slug($fullName, '_');
        $fullName = strtoupper($fullName);

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        // PROSECUTOR CLEARANCE
        if ($request->hasFile('prosecutor_clearance')) {

            $file = $request->file('prosecutor_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'PROSECUTOR_CLEARANCE_' . $fullName . '.' . $extension;

            $clearance->prosecutor_clearance = $file->storeAs(
                'clearances/prosecutor',
                $fileName,
                'public'
            );
        }

        // MUNICIPAL TRIAL COURT
        if ($request->hasFile('mtc_clearance')) {

            $file = $request->file('mtc_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'MTC_CLEARANCE_' . $fullName . '.' . $extension;

            $clearance->mtc_clearance = $file->storeAs(
                'clearances/mtc',
                $fileName,
                'public'
            );
        }

        // REGIONAL TRIAL COURT
        if ($request->hasFile('rtc_clearance')) {

            $file = $request->file('rtc_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'RTC_CLEARANCE_' . $fullName . '.' . $extension;

            $clearance->rtc_clearance = $file->storeAs(
                'clearances/rtc',
                $fileName,
                'public'
            );
        }

        // NBI CLEARANCE
        if ($request->hasFile('nbi_clearance')) {

            $file = $request->file('nbi_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'NBI_CLEARANCE_' . $fullName . '.' . $extension;

            $clearance->nbi_clearance = $file->storeAs(
                'clearances/nbi',
                $fileName,
                'public'
            );
        }

        // BARANGAY CLEARANCE
        if ($request->hasFile('barangay_clearance')) {

            $file = $request->file('barangay_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'BARANGAY_CLEARANCE_' . $fullName . '.' . $extension;

            $clearance->barangay_clearance = $file->storeAs(
                'clearances/barangay',
                $fileName,
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE CLEARANCE
        |--------------------------------------------------------------------------
        */

        $clearance->save();

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Clearance updated successfully.');
    }
}