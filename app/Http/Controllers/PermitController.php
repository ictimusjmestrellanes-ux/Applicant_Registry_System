<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\MayorsPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    public function update(Request $request, $id)
    {

        $applicant = Applicant::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE PERMIT
        |--------------------------------------------------------------------------
        */

        $permit = MayorsPermit::firstOrNew([
            'applicant_id' => $applicant->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PREPARE FILE NAME
        |--------------------------------------------------------------------------
        */

        $fullName = $applicant->last_name;
        $fullName = Str::lower(Str::slug($fullName, '_'));

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOADS
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('health_card')) {

            $file = $request->file('health_card');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'Health_Card_'.$fullName.'_'.$extension.'.';

            $permit->health_card = $file->storeAs(
                'permits/health_cards',
                $fileName,
                'public'
            );
        }

        if ($request->hasFile('nbi_or_police_clearance')) {

            $file = $request->file('nbi_or_police_clearance');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'NBI_or_Police_Clearance_'.$fullName.'_'.$extension.'.';

            $permit->nbi_or_police_clearance = $file->storeAs(
                'permits/nbi_clearance',
                $fileName,
                'public'
            );
        }

        if ($request->hasFile('cedula')) {

            $file = $request->file('cedula');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'Cedula_'.$fullName.'_'.$extension.'.';

            $permit->cedula = $file->storeAs(
                'permits/cedula',
                $fileName,
                'public'
            );
        }

        if ($request->hasFile('referral_letter')) {

            $file = $request->file('referral_letter');
            $extension = $file->getClientOriginalExtension();

            $fileName = 'Referral_Letter_'.$fullName.'_'.$extension.'.';

            $permit->referral_letter = $file->storeAs(
                'permits/referral_letters',
                $fileName,
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE FORM DATA
        |--------------------------------------------------------------------------
        */
        // Save FIRST (so it gets ID)
        $permit->fill([
            'permit_or_no' => $request->permit_or_no,
            'community_tax_no' => $request->community_tax_no,
            'permit_issued_on' => $request->permit_issued_on,
            'permit_date' => $request->permit_date,
            'expires_on' => $request->expires_on,
            'permit_doc_stamp_control_no' => $request->permit_doc_stamp_control_no,
            'permit_date_of_payment' => $request->permit_date_of_payment,
        ]);

        $permit->save();

        /*
        |---------------------------------------------------
        | AUTO GENERATE PESO ID (AFTER SAVE)
        |---------------------------------------------------
        */
        if (empty($permit->peso_id_no)) {

            $year = date('Y');

            $latest = MayorsPermit::whereYear('created_at', $year)
                ->whereNotNull('peso_id_no')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $latest
                ? ((int) substr($latest->peso_id_no, -4)) + 1
                : 1;

            $permit->peso_id_no =$year.'-'.str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

            $permit->save(); // SAVE AGAIN with generated ID
        }

        return redirect()
            ->route('applicants.edit', $applicant->id)
            ->with('success', 'Permit updated successfully.');
    }
}
